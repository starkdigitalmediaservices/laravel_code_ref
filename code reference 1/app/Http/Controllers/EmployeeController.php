<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\EmployeeRepository;
use App\Http\Requests\CreateUpdateEmployeeRequest;

class EmployeeController extends Controller
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
     * @param object $rows
     * @return object
     */
    public function index()
    {
        $employee = $this->employeeRepository->getEmployeeList();
        return view('employee.index', compact('employee'));
    }

    /**
     * Return a json data of the user.
     *
     * @param 
     * @return dept object
     */
    public function create()
    {
        $getDeptList = $this->employeeRepository->getDeptList();
        return view('employee.create', compact('getDeptList'));
    }

    /**
     * Return a obj data of the employee.
     *
     * @param Request $request
     * @param object $rows
     * @return object
     */
    public function store(CreateUpdateEmployeeRequest $request)
    {
         $responseData = $this->employeeRepository->storeEmployee($request);
        return redirect('/employees')->with('success', $responseData['msg']);
    }

    /**
     * Return a obj data of the employee.
     *
     * @param emoployee_id
     * @return object
     */
    public function edit($id)
    {
        $getDeptList = $this->employeeRepository->getDeptList();
        $emloyeeData = $this->employeeRepository->getEmployeeById($id);
        return view('employee.edit', compact('getDeptList','emloyeeData'));
    }

     /**
     * Delete employee data
     *
     * @param object $rows
     * @return object
     */
    public function destroy($id)
    {
        $emloyeeData = $this->employeeRepository->destroyEmployeeRecord($id);
        return redirect('/employees')->with('success', $emloyeeData['msg']);
    }

    public function getReport(Request $request) {
        return $this->employeeRepository->getReport($request);
    }



}
