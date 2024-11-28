{extends file="layouts/base.tpl"}

{block name="content"}
<div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h1 class="card-title m-0">Patient List</h1>
                            <a href="patients.php?action=add" class="btn btn-primary">Add Patient</a>
                        </div>
                        <table class="table table-bordered table-hover text-center mb-3">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Department</th>
                                    <th style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {if $patients|@count > 0}
                                    {foreach $patients as $patient}
                                        <tr>
                                            <td>{$patient.id}</td>
                                            <td>{$patient.name}</td>
                                            <td>{$patient.mobile}</td>
                                            <td>{$patient.appointment_date}</td>
                                            <td>{$patient.doctor_name}</td>
                                            <td>{$patient.department_name}</td>
                                            <td class="d-flex gap-2">
                                                <a href="patients.php?action=edit&id={$patient.id}" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="patients.php?action=delete&id={$patient.id}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this patient?')">Delete</a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                {else}
                                    <tr>
                                        <td colspan="7">No patients found.</td>
                                    </tr>
                                {/if}
                            </tbody>
                        </table>
                        {include file="partials/pagination.tpl" total_pages=$pagination.total_pages current_page=$pagination.current_page search=$search}
                    </div>
                </div>

{/block}

{assign var="page" value="list"}
