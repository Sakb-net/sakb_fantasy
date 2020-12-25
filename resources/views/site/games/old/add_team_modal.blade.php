<div id="enterTeamModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">إدخال الفريق</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="game.html" method="post">
                            <div class="form-group">
                                <label>حدد اسم لفريقك</label>
                                <input type="text" name="team-name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>قم باختيار النادي المفضل لك:</label>
                                <select class="form-control">
                                    <option>النادي الأهلي</option>
                                    <option>نادي التعاون</option>
                                    <option>نادي الاتحاد</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" class="" name="approve" placeholder="" required>
                                <label for="approve">لقد قمت بقراءة <a href="rules.html">الشروط و الأحكام</a></label>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="submit" name="login-submit" id="login-submit" class="butn butn-bg" value="إدخال الفريق">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>