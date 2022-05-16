<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\EmployeeRepository;
use App\Http\Requests\CreateUpdateEmployeeAPIRequest;

class ApiEmployeeController extends Controller
{
    private $employeeRepository;

    public function __construct(
        EmployeeRepository $employeeRepository,
    ){
        $this->employeeRepository = $employeeRepository;
    }

    /**
     * Return a obj data of the employee.
     *
     * @param Request $request
     * @param json $rows
     * @return json
     */
    public function getEmployeeList()
    {
        $employee = $this->employeeRepository->getEmployeeList();

        return response()->json([
            "success" => true,
            "message" => "Employee data listed successfully.",
            "data"    => $employee
        ]);

    }

    /**
     * Return a obj data of the employee.
     *
     * @param Request $request
     * @param json $rows
     * @return json
     */
    public function createUpdateEmployee(CreateUpdateEmployeeAPIRequest $request)
    {

         $responseData = $this->employeeRepository->storeEmployee($request);

         return response()->json([
             "success" => true,
             "message" => $responseData['msg'],
             "data" => [
                'id' => $responseData['id'],
             ],
         ]);
    }

    /**
     * Return a obj data of the employee.
     *
     * @param emoployee_id
     * @return json
     */
    public function edit($id)
    {
        $getDeptList = $this->employeeRepository->getDeptList();
        $emloyeeData = $this->employeeRepository->getEmployeeById($id);
        
        return response()->json([
            "success" => true,
            "message" => $emloyeeData['msg'],
        ]);
    }

     /**
     * Delete employee data
     *
     * @param json $rows
     * @return json
     */
    public function destoryEmployeeData($id)
    {
        $emloyeeData = $this->employeeRepository->destroyEmployeeRecord($id);

        return response()->json([
            "success" => true,
            "message" => $emloyeeData['msg'],
        ]);
    }

}
