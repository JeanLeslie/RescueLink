<?php require_once '../lib/layout.php'; ?>
<?php $title = 'Rescue Link - Users' ?>
<section>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Email Address</th>
                            <th>Device Name</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Email Address</th>
                            <th>Device Name</th>
                            <th>IP Address</th>
                        </tr>
                    </tfoot>
                    <tbody id="usersTableTbody">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="display:none;">
    <tbody id="usersTableTbodyTemplate">
        <tr class="odd_even">
            <td class="sorting_1">Number</td>
            <td>EmailAdd</td>
            <td>DeviceName</td>
            <td>IPAddress</td>
            
        </tr>
    </tbody>
</table>
<script src= "../js/users.js"></script>
