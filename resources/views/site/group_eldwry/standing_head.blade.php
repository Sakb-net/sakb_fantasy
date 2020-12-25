<div class="col-md-10 standing_head hidden">
    <div class="nav" role="tablist">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active">
                <a class="nav-link" data-toggle="tab" href="#help" role="tab">
                    {{trans('app.standings')}}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#rules" role="tab">
                    المباريات
                </a>
            </li>
        </ul>
    </div>
    <!-- Tab Content Start -->
    <div class="tab-content" id="myTabContent">
        <!-- Single Tab Content Start -->
        <div class="tab-pane fade active in" id="help" role="tabpanel">
            <div class="mytab-content">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <h2>اسم الدوري</h2>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group ">
                            <label> {{trans('app.show_according')}}:</label>
                            <select data-type="head" class="form-control filter_groupEldwry  subeldwry_groupEldwry">
                               <!--  <option>كل الموسم</option>
                                <option>أغسطس</option>
                                <option>سبتمبر</option>
                                <option>أكتوبر</option>
                                <option>نوفمبر</option>
                                <option>ديسمبر</option>
                                <option>يناير</option>
                                <option>فبراير</option>
                                <option>مارس</option>
                                <option>ابريل</option>
                                <option>مايو</option> -->
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table league-table draw_data_group_eldwry">
                </table>
                <!-- end panel group -->
            </div>
        </div>
        <!-- Single Tab Content End -->

        <!-- Single Tab Content Start -->
        <div class="tab-pane fade" id="rules" role="tabpanel">
            <div class="mytab-content">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <h2>اسم الدوري</h2>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group ">
                            <label> {{trans('app.show_according')}}:</label>
                            <select data-type="head" class="form-control filter_groupEldwry  subeldwry_groupEldwry">

                            </select>
                        </div>
                    </div>
                </div>
                <div id="table-wrapper">
                    <div id="table-scroll">
                        <table class="table league-table draw_data_head_group_eldwry">
                        </table>
                    </div>
                </div>
                <!-- end panel group -->
            </div>
        </div>
        <!-- Single Tab Content End -->
    </div>
    <!-- Tab Content End -->
</div> 