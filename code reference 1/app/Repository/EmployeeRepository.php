<?php

namespace App\Repository;
use App\Models\Employee;
use App\Models\Department;
use DB;
class EmployeeRepository
{

    public function getEmployeeList() {
        return Employee::all();
    }

    public function getDeptList() {
        return Department::pluck('name', 'id');
    }

    public function storeEmployee($request) {

        $returnParam = array();

        $employeeData = $request->all();
        $employeeData['hobbies'] = implode(",", $employeeData['hobby']);

        if(!empty($employeeData['_token'])) { 
            unset($employeeData['_token']);
        }

        if(!empty($employeeData['hobby'])) { 
            unset($employeeData['hobby']);
        }

        if(!empty($employeeData['id'])) {

            Employee::whereId($employeeData['id'])->update($employeeData);
            $returnParam['id'] = $employeeData['id'];
            $returnParam['msg'] = 'Employees has been updated!';


        } else {
            $employeeData = Employee::create($employeeData);
            $returnParam['id'] = $employeeData['id'];
            $returnParam['msg'] = 'Employees has been created!';
        }

        return $returnParam;

    }

    public function getEmployeeById($id) {
        return  Employee::findOrFail($id);
    }


    public function destroyEmployeeRecord($id) {
        $employeeData = $this->getEmployeeById($id);
        $employeeData->delete();

        $returnParam['msg'] = 'Employees has been deleted!';

        return $returnParam;
    }

    public function getReport($request) {
        $returnString = '';
        if($request->type == '2nd_highest') {

            $response =  Employee::select('name', 'salary')->orderBy('salary', 'desc')
                                   ->take(1)
                                   ->skip(1)
                                   ->first()->toArray();

            $filterResponse = $this->filterResponse($response);

            $returnString = $filterResponse;

        } else if($request->type == '5th_highest') {

            $response =  Employee::select('name', 'salary')->orderBy('salary', 'desc')
                                   ->take(1)
                                   ->skip(4)
                                   ->first()->toArray();

            $filterResponse = $this->filterResponse($response);

            $returnString = $filterResponse;


        } else if($request->type == 'Avg_salary_by_dept') {

            $response = DB::table('employees')
                        ->selectRaw('departments_id, AVG(salary) AS avg_salary')
                        ->groupBy('employees.departments_id')
                        ->get()->toArray();

            if(!empty($response)) {

                $returnString = '';
                $deptData = $this->getDeptList()->toArray();

                $count = 0;
                foreach ($response as $value) {
                    $sttSpt = '';
                    if($count > 0) $sttSpt = ", "; 

                    $returnString .= $sttSpt.$deptData[$value->departments_id]. ': '.$value->avg_salary;

                    $count++;
                }

            }

        }


        return $returnString;

    }


    function filterResponse($response) {

        $returnString = '';

        if(!empty($response)) {
            $returnString = $response['name'].': '.$response['salary'];
        }

        return $returnString;
    }



}