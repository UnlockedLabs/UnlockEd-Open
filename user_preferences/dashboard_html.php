    <div class="profile-cover">
        <div class="profile-cover-img animated fadeIn" id="banner" style="background-image: url(./user_preferences/images/banner<?php echo $bannerNum ?>.jpg)"></div>
        <div class="media align-items-center text-center text-md-left flex-column flex-md-row m-0">
            
            <!-- bottom right floating menu in cover image -->
            <ul class="fab-menu fab-menu-absolute fab-menu-bottom-right animated fadeIn" data-fab-toggle="hover" id="fab-menu-affixed-demo-right">
                <li>
                    <a class="fab-menu-btn btn bg-transparent btn-float rounded-round btn-icon">
                        <i class="fab-icon-open icon-more2 text-light"></i>
                        <i class="fab-icon-close icon-cross2 text-dark"></i>
                    </a>

                    <ul class="fab-menu-inner">
                        <li>
                            <div data-fab-label="Day / Night">
                                <button href="#" class="btn btn-link text-light bg-grey-300 rounded-round btn-icon btn-float" id="toggleNightMode" onclick="toggleNightMode('<?php echo $nightMode;?>');">
                                    <i class="fas fa-adjust"></i>
                                </button>
                            </div>
                        </li>
                        <li>
                            <div data-fab-label="Cover Photo">
                                <button href="#" class="btn bg-orange-800 rounded-round btn-icon btn-float" id="show_image_select">
                                    <i class="fas fa-image"></i>
                                </button>
                            </div>
                        </li>
                        <li>
                            <div data-fab-label="Color Theme">
                                <button href="#" class="btn rounded-round btn-icon btn-float" id="show_color_pallet">
                                    <img src="./user_preferences/images/colors.png" class="img-fluid rounded-circle" alt="">
                                </button>
                            </div>
                        </li>
                        <!--li>
                            <div data-fab-label="My Account">
                                <button href="#" class="btn bg-danger-300 rounded-round btn-icon btn-float">
                                    <i class="fas fa-user-cog"></i>
                                </button>
                            </div>
                        </li-->
                    </ul>
                </li>
            </ul>
            <!-- /bottom right floating menu in cover image -->
        </div>
    </div>

    <!-- Widgets list -->
    <div class="row mt-4">
        <div class="col-md-6 col-xl-4">
            
            <!-- Personal greeting and time/date display -->
            <div class="card card-body ue_color_theme rounded text-light border-1 invisible" id="dashboardGreeting" style="background-image: url(./user_preferences/images/swirl_paper.png); background-color: <?php echo $dashboardColor; ?>">
                <div class="media">
                    <div class="mr-3 align-self-center">
                        <img src="./images/UELogo_white.png" width="40px">
                    </div>

                    <div class="media-body text-right">
                        <h6 class="media-title font-weight-semibold">Good<span id="timeOfDay"></span><?php echo ucwords($_SESSION['username']); ?></h6>
                        <span class="opacity-75">Today is <?php echo date("F d, Y"); ?></span>
                    </div>
                </div>

                <div class="card-body border-top-1 mt-4" style="">
                    <div class="d-flex justify-content-center mb-2 text-light">
                        <p class="mr-2 opacity-50" id="day0">SUN</p>
                        <p class="mr-2 opacity-50" id="day1">MON</p>
                        <p class="mr-2 opacity-50" id="day2">TUE</p>
                        <p class="mr-2 opacity-50" id="day3">WED</p>
                        <p class="mr-2 opacity-50" id="day4">THU</p>
                        <p class="mr-2 opacity-50" id="day5">FRI</p>
                        <p class="opacity-50" id="day6">SAT</p>
                    </div>

                    <div class="d-flex justify-content-center text-center">
                        <div class="timer-number font-weight-light">
                            <span id="dash_hour"></span> <span class="d-block font-size-base mt-2">hours</span>
                        </div>
                        <div class="timer-dots mx-1 mb-4">:</div>
                        <div class="timer-number font-weight-light">
                            <span id="dash_min"></span> <span class="d-block font-size-base mt-2">minutes</span>
                        </div>
                    </div>

                </div>
            </div>   
            <!-- /Personal greeting and time/date display --> 

        </div>
    

        <div class="col-md-6 col-xl-4">
            
            <!-- My tasks -->
            <div class="card rounded invisible" id="myTasks">
                <div class="card-header text-light ue_color_theme text-center pt-1 pb-1" style="background-image: url(./user_preferences/images/arrows.png); background-color: <?php echo $dashboardColor; ?>">
                    <h5 class="my-auto"><i class="mi-done-all mi-2x"></i> My Tasks</h5>
                </div>

                <div class="card-body mt-2">
                    <div class="form-group" id="taskList">
                        <?php include_once 'tasks.php'; ?>
                    </div>
                    <div class="input-group">
                        <input class="form-control" id="newTask" maxlength="50" placeholder="New Task" type="text">
                        <span class="input-group-append">
                            <button class="btn bg-green" type="button" onclick="addTask();"><i class="icon icon-plus2"></i></button>
                        </span>
                    </div>

                </div>
            </div>
            <!-- /my tasks -->

        </div>


        <div class="col-xl-4 col-md-12">

            <!-- Quote -->
            <div class="card bg-slate-800 text-white rounded text-center border-1 p-3 ue_color_theme invisible" id="quoteGenerator" style="background-image: url(./user_preferences/images/scallop_paper.png); background-size: contain; background-color: <?php echo $dashboardColor; ?>">
                <div>
                    <button class="btn btn-lg btn-icon mb-3 mt-1 btn-outline text-white border-white bg-white rounded-round border-2" id="refreshQuote">
                        <i class="ua-icon ua-icon-quote icon-2x"></i>
                    </button>
                </div>

                <blockquote class="blockquote" id="blockquote">
                    <h5 class="font-weight-light" id="quote"></h5>
                    <footer class="blockquote-footer text-white">
                        <span id="quoteAuthor"></span>
                    </footer>
                </blockquote>
            </div>
            <!-- /Quote -->

        </div>
    </div>


    <!-- color picker, hidden by d-none until removed by PNotify in user_preferences.js -->
    <div id="color_pallet" class="d-none">
        <div class="text-center">
            <a href="#" class="badge badge-float bg-slate-300 rounded-circle badge-icon" id="close"><i class="icon-cross3"></i></a>
            <div class="btn-group">
                <button type="button" class="btn btn-sm border-light bg-primary-800" onclick="changeColor('#1565C0');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-danger-800" onclick="changeColor('#C62828');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-success-800" onclick="changeColor('#2E7D32');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-warning-800" onclick="changeColor('#D84315');"><span class="opacity-0">#</span></button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm border-light bg-info-800" onclick="changeColor('#00838F');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-pink-600" onclick="changeColor('#D81B60');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-orange-800" onclick="changeColor('#EF6C00');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-brown-600" onclick="changeColor('#6D4C41');"><span class="opacity-0">#</span></button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm border-light bg-violet-800" onclick="changeColor('#6A1B9A');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-purple-800" onclick="changeColor('#4527A0');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-indigo-600" onclick="changeColor('#3949AB');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-blue-800" onclick="changeColor('#0277BD');"><span class="opacity-0">#</span></button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-sm border-light bg-teal-700" onclick="changeColor('#00796B');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-green-800" onclick="changeColor('#558B2F');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-grey-700" onclick="changeColor('#555555');"><span class="opacity-0">#</span></button>
                <button type="button" class="btn btn-sm border-light bg-slate-700" onclick="changeColor('#455A64');"><span class="opacity-0">#</span></button>
            </div>
        </div>
    </div>
    <!-- color picker -->


    <!-- image picker, hidden by d-none until removed by PNotify in user_preferences.js -->
    <div id="image_select" class="d-none">
        <div class="text-center mt-2 mb-2">
            <a href="#" class="badge badge-float bg-slate-300 rounded-circle badge-icon" id="close"><i class="icon-cross3"></i></a>
            <div class="btn-group">
                <a href="#" class="p-2" onclick="selectBanner(1);"><img src="./user_preferences/images/banner1.jpg" width="200px"></a>
                <a href="#" class="p-2" onclick="selectBanner(2);"><img src="./user_preferences/images/banner2.jpg" width="200px"></a>
                <a href="#" class="p-2" onclick="selectBanner(3);"><img src="./user_preferences/images/banner3.jpg" width="200px"></a>
                <a href="#" class="p-2" onclick="selectBanner(4);"><img src="./user_preferences/images/banner4.jpg" width="200px"></a>
            </div>
            <div class="btn-group">
                <a href="#" class="p-2" onclick="selectBanner(5);"><img src="./user_preferences/images/banner5.jpg" width="200px"></a>
                <a href="#" class="p-2" onclick="selectBanner(6);"><img src="./user_preferences/images/banner6.jpg" width="200px"></a>
                <a href="#" class="p-2" onclick="selectBanner(7);"><img src="./user_preferences/images/banner7.jpg" width="200px"></a>
                <a href="#" class="p-2" onclick="selectBanner(8);"><img src="./user_preferences/images/banner8.jpg" width="200px"></a>
            </div>
        </div>
    </div>
    <!-- image picker -->