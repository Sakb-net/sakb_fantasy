<div class="box">
    <div class="box-body">
        <label> {{trans('app.help')}} </label>
        <div class="clearfix m-b"></div>
        <div class="raw title-repeater">
            <div  data-repeater-list="titleinstraction" >
                @foreach ($content_items as $key_item => $val_item)
                <div  data-repeater-item>
                    <div class="col-sm-10"> 
                        <label> {{trans('app.title')}} :</label>
                        {!! Form::text('name', $val_item->content_value, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-sm-10"> 
                        <label> {{trans('app.content')}} :</label>
                        <!--,'id'=>'my-textarea'-->
                        {!! Form::textarea('content_item',$val_item->content_etc, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-sm-1 bi-input">
                        <input data-repeater-delete type="button" class="btn btn-danger fa fa-remove" value="&#xf00d"/>
                    </div> 
                     <div class="clearfix m-b"></div> <hr/>
                </div> 
                @endforeach
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3 col-sm-4">
            <input  type="button" class="btn btn-success btn-s-xs title-add-show" value=" {{trans('app.add')}}   {{trans('app.help')}}"/>
        </div>
        <div class="clearfix"></div>
        <div class="raw title-add-repeater hide">
            <div  data-repeater-list="title_addinstraction" >
                <div  data-repeater-item>
                    <div class="col-sm-10"> 
                        <label> {{trans('app.name')}} :</label>
                        {!! Form::text('name', null, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-sm-10"> 
                        <label> {{trans('app.content')}} :</label>
                        <!--,'id'=>'my-textarea'-->
                        {!! Form::textarea('content_item',null, array('class' => 'form-control')) !!}
                    </div>
                    <div class="col-sm-1 bi-input">
                        <input data-repeater-delete type="button" class="btn btn-danger fa fa-remove" value="&#xf00d"/>
                    </div> 
                    <div class="clearfix m-b"></div> <hr/>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 m-b">
                <input data-repeater-create type="button" class="btn btn-success btn-s-xs" value=" {{trans('app.add')}}  {{trans('app.help')}}"/>
            </div>
        </div>
    </div>
</div>