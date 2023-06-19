<!-- Pnotify coin panel (hidden by class d-none until revealed by pnotify js function in gamification.js)-->
<div id="coin_info" class="d-none" style="width:300px">
    <a href="#" class="badge badge-float bg-slate-300 rounded-circle badge-icon" id="close"><i class="icon-cross3"></i></a>
    <div class="text-center">
        <div class="card bg-dark rounded border-3 shadow" id="status_card">
            <div class="text-center">
                <img class="mb-2 mt-2" src="./images/UELogo.png" width="80%" />
                <h3 class="font-weight-semibold"><span id="panel_status" class="text-<?php echo $levelColor . '">' . $userStatus; ?></span></h3>
                <p id="next_level_info">To unlock <span id="next_level">next level</span> status,<br>collect <span id="next_level_coins">0</span> coins</p>
            </div>
        </div>
    </div>

    <div class="text-center">
        <div class="mt-3 bg-slate">
            <h1 class="mb-0 coin_count">
                <?php echo $coinsDelimeter; ?>
            </h1>
            <h4>Coins Collected</h4>
            <img src="./images/gamification/coins/gold_<?php echo $userLevel; ?>.png" id="info_coin_img" width="20%" />
        </div>
        <div class="mt-3 bg-slate text-center">
            <h4>Your Rank</h4>
            <h1><?php echo $userRank; ?></h1> <!-- GET FROM DB -->
        </div>
    </div>
</div>
<!-- / Pnotify coin panel -->

<!-- custom modal for course completion and welcome to new user -->
<div id="game_modal" class="modal fade" data-backdrop="static">
    <div id="show_game_modal"></div>
    <div class="modal-dialog modal-full">
        <div class="modal-content text-center">
            <video width="100%" id="confetti" autoplay loop>
                <source src="./images/gamification/course_complete/confetti.ogg" type="video/ogg">
            </video>
            <div id="course_complete" class="row">
                <div class="col-4">
                    <div class="bg-transparent border-transparent mt-5 animated zoomInLeft">
                        <div class="card-body">
                            <img src="" id="img_left" class="img-fluid animated tada" />
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div id="center_block" class="bg-transparent border-transparent rounded mt-5">
                        <div id="collect_modal_header" class="card-head bg-dark p-2">
                            <img src="./images/UELogo.png" width="100%">
                        </div>
                        <div class="card-body">
                            <div class="font-weight-thin">
                                <span id="greeting" class="">GREETING</span>
                                <span class="mb-2" id="course_name">COURSE NAME</span>
                            </div>

                            <div>
                                <i id="click_here_arrow" class="icon icon-arrow-down8 icon-3x text-muted animated bounce mb-3"></i>
                            </div>

                            <button type="button" id="collect_btn" class="btn bg-green btn-rounded btn-float shadow"
                            data-dismiss="modal"><img src="./images/gamification/coins/collect_coins.png" width="50px"><span class="ml-5 mr-5 font-weight-bold">COLLECT COINS</span></button>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="bg-transparent border-transparent mt-5 animated zoomInRight">
                        <div class="card-body">
                            <img src="" id="img_right" class="img-fluid animated tada" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /custom modal -->