<?php require_once '../lib/layout.php'; ?>
<?php $title = 'Rescue Link - Devices' ?>
<section>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Devices</h1>
    </div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Devices</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Device Name</th>
                            <th>IP Address</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                        <th>No.</th>
                            <th>Device Name</th>
                            <th>IP Address</th>
                            <th>Location</th>
                        </tr>
                    </tfoot>
                    <tbody id="devicesTableTbody">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"  style="display:none;">
    <tbody id="devicesTableTbodyTemplate">
        <tr class="odd_even">
            <td class="sorting_1">Number</td>
            <td>DeviceName</td>
            <td>IPAddress</td>
            <td>Location_val</td>
            
        </tr>
    </tbody>
</table>
<script src= "../js/devices.js?v=2"></script>
