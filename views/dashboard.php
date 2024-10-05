<?php 
// session_start();
require_once '../lib/layout.php'; 

$title = 'Rescue Link - Dashboard' ;
?>
<section>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    
    <div class="row">
    
        <div class="col-xl-3" id="divLeftSide">
            <div class="row justify-content-center" id="divCardDetected">
            </div>
        </div>
        <div class="col" id="divRightSide">
            <div class="row">

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2" id="divOnGoingFire">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-uppercase mb-1">ON GOING FIRE</div>
                                    <div class="text-xs font-weight-bold">Ip Address: <span id="fire_IPAdd">NONE</span></div>
                                    <div class="text-xs font-weight-bold">Location: <span id="fire_Loc">NONE</span></div>
                                    <div class="text-xs font-weight-bold">Start Time: <span id="fire_Time">NONE</span></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-fire fa-4x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-6 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2" id="divOnGoingSmoke">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="h5 mb-0 font-weight-bold text-uppercase mb-1">ON GOING SMOKE</div>
                                        <div class="text-xs font-weight-bold">Ip Address: <span id="smoke_IPAdd">NONE</span></div>
                                        <div class="text-xs font-weight-bold">Location: <span id="smoke_Loc">NONE</span></div>
                                        <div class="text-xs font-weight-bold">Start Time: <span id="smoke_Time">NONE</span></div>
                                    </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-smog fa-4x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">History</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead >
                                <tr>
                                    <th class="sorting" >Start date - time</th>
                                    <th>Detected</th>
                                    <th>Name</th>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Start date - time</th>
                                    <th>Detected</th>
                                    <th>Name</th>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                            <tbody id="dashboardTbody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="cardStatusTemplate" style="display:none;">
        <!-- <div class="col-lg-4 col-md-6"> -->
            <div class="card shadow border-left-color mb-4 mx-3 w-100" id="cardStatusNumber">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-color">DetectionType</h6>
                </div>
                <div class="card-body">
                    <div class="text-center"style="min-height: 16rem"  id="cardStatusImgNumber">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="max-height: 15rem;" src="IMG_SOURCE" alt="...">
                    </div>
                    <p><b>Date / Time: </b> DateTime</br>
                    <b>IP Address: </b>IPAddress</br>
                    <b>Location: </b>Location_val</br>
                    <b>Level: </b>Level_value</br>
                    </p>
                    <!-- <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                        unDraw →</a> -->
                </div>
            </div>
        <!-- </div> -->
    </div>
    
    <div id="cardFireTemplate" style="display:none;">
        <!-- <div class="col-lg-4 col-md-6"> -->
            <div class="card shadow border-left-danger mb-4 mx-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">FIRE</h6>
                </div>
                <div class="card-body">
                    <div class="text-center"style="min-height: 16rem">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="max-height: 15rem;" src="IMG_SOURCE" alt="...">
                    </div>
                    <p><b>Date / Time: </b> DateTime</br>
                    <b>IP Address: </b>IPAddress</br>
                    <b>Location: </b>Location_val</br>
                    <!-- <b>Level: </b>Level_value</br> -->
                    </p>
                    <!-- <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                        unDraw →</a> -->
                </div>
            </div>
        <!-- </div> -->
    </div>
    <div id="cardSmokeTemplate"  style="display:none;">
        <!-- <div class="col-lg-4 col-md-6"> -->
            <div class="card shadow border-left-success mb-4 mx-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Smoke</h6>
                </div>
                <div class="card-body">
                    <div class="text-center" style="min-height: 16rem">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style=" max-height: 15rem;" src="IMG_SOURCE" alt="...">
                    </div>
                    <p><b>Date / Time: </b> DateTime</br>
                    <b>IP Address: </b>IPAddress</br>
                    <b>Location: </b>Location_val</br>
                    <!-- <b>Level: </b>Level_value</br> -->
                    </p>
                    <!-- <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                        unDraw →</a> -->
                </div>
            </div>
        <!-- </div> -->
    </div>
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0"   style="display:none;">
        <tbody id="dashboardTbodyTemplate">
            <tr class="odd_even bg-detect">
                <td>StartDate</td>
                <td>Detection</td>
                <td>DeviceName</td>
                <td>IPAddress</td>
                <td>Location</td>
                <td>Status</td>
            </tr>
        </tbody>
    <table>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../js/demo/datatables-demo.js"></script>
    <script src= "../js/dashboard.js?v=1"></script>
</section>