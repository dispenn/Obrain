<aside class="right-side">
  <section class="content-header"><h1>Ссылка на анкету</h1></section>
  <section class="content">
    <div class="row">
        <div class="col-md-12">

            <form class="form-horizontal" role="form" method="post" action="<?php echo base_url("administrator/anketa"); ?>">

              <div class="row">
                <label class="col-sm-2 control-label">Ссылка</label>
                <div class="col-md-8">
                    <textarea style="width: 100% !important;" name="form[link]"  id="company" rows="10"><?php if(isset($form['link'])) {echo $form['link'];} else {echo set_value('form[link]');}?></textarea>
                </div>
              </div>
            <br />
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <input class="btn btn-success" name="create" type="submit" value="Сохранить">
                </div>
              </div>
            </form>   
            
            
            
            
        </div><!--/span-->
    </div><!--/row-->

    </section><!-- /.content -->
    
</aside><!-- /.right-side -->

