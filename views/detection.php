<?php require_once '../lib/layout.php'; ?>
<?php $title = 'Rescue Link - Detections' ?>
<section>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detections</h1>
    </div>
    <div class="row align-items-center" id="detectionCards">
        
    </div>
<section>

<div id="detectionStatusTemplate" style="display:none;">
    <div class="col-lg-4 col-md-6">
        <div class="card shadow border-left-color mb-4 w-100" id="cardStatusNumber">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-color">DetectionType</h6>
            </div>
            <div class="card-body">
                <div class="text-center"style="min-height: 16rem"  id="cardStatusImgNumber">
                    <a href="GDrive_Link" target="_blank">

                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="max-height: 15rem;" src="IMG_SOURCE" alt="...">
                    </a>

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
    </div>
</div>
<div id="detectionFireTemplate" style="display:none;">
    <div class="col-lg-4 col-md-6">
        <div class="card shadow border-left-danger mb-4">
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
                    <b>Level: </b>Level_value</br>
                    </p>
                <!-- <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                    unDraw →</a> -->
            </div>
        </div>
    </div>
</div>
<div id="detectionSmokeTemplate"  style="display:none;">
    <div class="col-lg-4 col-md-6">
        <div class="card shadow border-left-success mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Smoke</h6>
            </div>
            <div class="card-body">
                <div class="text-center" style="min-height: 16rem">
                    <a href = GDrive_Link target="_blank">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style=" max-height: 15rem;" src="IMG_SOURCE" alt="..." >
                    </a>
                </div>
                <p><b>Date / Time: </b> DateTime</br>
                <b>IP Address: </b>IPAddress</br>
                <b>Location: </b>Location</br>
                <b>Level: </b>Level_value</br>
                </p>
                <!-- <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                    unDraw →</a> -->
            </div>
        </div>
    </div>
</div>
<script src= "../js/detection.js?v=2"></script>
