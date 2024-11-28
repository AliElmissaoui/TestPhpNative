{extends file="layouts/base.tpl"}

{block name="content"}
<div class="container-fluid">
   
    <div class="row">
        <div class="col-md-12">
            <div class="card card-teal card-outline mb-4 text-size-13">
                <div class="card-header">
                    <div class="card-title text-bold">{if isset($patient.id)}Edit Patient{else}Add Patient{/if}</div>
                </div>
                <form method="POST">
                    <div class="card-body mx-3 mt-2">

                        {if $errors}
                        <div class="alert alert-danger">
                            <ul>
                                {foreach $errors as $error}
                                <li>{$error}</li>
                                {/foreach}
                            </ul>
                        </div>
                        {/if}

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{$patient.name|default:''}" required />
                        </div>

                        <div class="mb-3">
                            <label for="mobile" class="form-label">Mobile</label>
                            <input type="text" class="form-control" id="mobile" name="mobile" value="{$patient.mobile|default:''}" />
                        </div>

                        <div class="mb-3">
                            <label for="appointment_date" class="form-label">Appointment Date</label>
                            <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" value="{$patient.appointment_date|default:''}" required />
                        </div>

                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-select" id="doctor_id" name="doctor_id" required>
                                {foreach $doctors as $doctor}
                                <option value="{$doctor.id}" {if isset($patient.doctor_id) && $patient.doctor_id == $doctor.id}selected{/if}>{$doctor.name}</option>
                                {/foreach}
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select" id="department_id" name="department_id" required>
                                {foreach $departments as $department}
                                <option value="{$department.id}" {if isset($patient.department_id) && $patient.department_id == $department.id}selected{/if}>{$department.name}</option>
                                {/foreach}
                            </select>
                        </div>

                    </div>
                    <div class="card-footer d-flex flex-row-reverse">
                        <button type="submit" class="btn bg-body-teal text-white my-2 me-3">Save</button>
                        <a href="patients.php" class="btn btn-secondary my-2 mx-3">Back to list</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>

{/block}


