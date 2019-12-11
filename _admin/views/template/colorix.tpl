<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <span class="btn">v<?php echo $version; ?></span>
        <button type="submit" form="form-theme-theme_default" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <button type="button" data-toggle="modal" data-target="#import-modal" class="btn btn-primary btn-import"><?php echo $text_import; ?></button>
        <a href="<?php echo $export; ?>" data-toggle="tooltip" title="<?php echo $button_export; ?>" class="btn btn-primary btn-export"><?php echo $text_export; ?></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($this->hasError('warning')) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php $this->error('warning'); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (isset($success) && $success) { ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title row">
          <div class="col-md-8" style="padding-top: 10px;">
            <h3><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
          </div>
          <div class="col-md-4">
            <select id="store_id" name="store_id" class="form-control">
              <?php foreach($stores as $store) {
                $selected = ($store['store_id'] == Input::get_post('store_id')) ? 'selected="selected"' : '';
                ?>
                <option value="<?php echo $store['redirect']; ?>" <?php echo $selected; ?>><?php echo $store['name']; ?> (<?php echo $store['url']; ?>)</option>
              <?php } ?>
            </select>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-theme-theme_default" class="form-horizontal">
            <div class="row row-eq-height">
                <div class="col-xs-3 col-sm-2">
                    <ul class="nav nav-tabs tabs-left-default">
                        <li class="active"><a href="#tab_general" data-toggle="tab"><?php echo $text_general; ?></a></li>
                        <li><a href="#tab_design" data-toggle="tab"><?php echo $text_design; ?></a></li>
                        <li><a href="#tab_header" data-toggle="tab"><?php echo $text_header; ?></a></li>
                        <li><a href="#tab_footer" data-toggle="tab"><?php echo $text_footer; ?></a></li>
                        <li><a href="#tab_contact" data-toggle="tab"><?php echo $text_contact; ?></a></li>
                        <li><a href="#tab_product" data-toggle="tab"><?php echo $text_product; ?></a></li>
                        <li><a href="#tab_image" data-toggle="tab"><?php echo $text_image; ?></a></li>
                    </ul>
                </div>
                <div class="col-xs-9 col-sm-10">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_general">
          <fieldset>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="col-sm-10">
                <select name="theme[status]" id="input-status" class="form-control">
                  <?php if ($this->data('theme.status')) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </fieldset>
                      </div>
                      <div class="tab-pane" id="tab_design">
          <fieldset>
            <legend><?php echo $text_design; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-color-scheme"><?php echo $entry_color_scheme; ?></label>
              <div class="col-md-2 col-sm-3">
                  <select name="theme[color_scheme]" id="input-color-scheme" class="form-control">
                  <?php foreach (array('blue', 'green', 'orange', 'red', 'black', 'blue_yellow') as $color) { ?>
                    <option value="<?php echo $color; ?>" <?php echo ($this->data('theme.color_scheme') == $color) ? 'selected="selected"' : ''; ?>><?php echo $color; ?></option>
                  <?php } ?>
                  <?php if($custom_css) { ?>
                    <?php foreach($custom_css as $custom) { ?>
                      <option value="<?php echo $custom; ?>" <?php echo ($this->data('theme.color_scheme') == $custom) ? 'selected="selected"' : ''; ?>><?php echo $custom_color; ?> (stylesheet_<?php echo $custom; ?>.css)</option>
                    <?php } ?>
                  <?php } ?>
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-background-color"><?php echo $entry_background_color; ?></label>
              <div class="col-md-2 col-sm-3">
                  <input type="text" name="theme[background_color]" class="jscolor {refine:false} form-control" value="<?php echo $this->data('theme.background_color'); ?>" id="input-background-color" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-background-image"><?php echo $entry_background_image; ?></label>
              <div class="col-md-2 col-sm-3">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $background_image_thumb; ?>" alt="" title="" /></a>
                  <input type="hidden" name="theme[background_image]" value="<?php echo $this->data('theme.background_image'); ?>" id="input-background-image" />
                <?php if ($this->hasError('background_image')) { ?>
                <div class="text-danger"><?php $this->error('background_image'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-background-position"><?php echo $entry_background_position; ?></label>
              <div class="col-md-2 col-sm-3">
                  <select name="theme[background_position]" id="input-background-position" class="form-control">
                  <?php foreach (array('center top', 'center bottom') as $position) { ?>
                    <option value="<?php echo $position; ?>" <?php echo ($this->data('theme.background_position') == $position) ? 'selected="selected"' : ''; ?>><?php echo $position; ?></option>
                  <?php } ?>
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-background-size"><?php echo $entry_background_size; ?></label>
              <div class="col-md-2 col-sm-3">
                  <select name="theme[background_size]" id="input-background-size" class="form-control">
                  <?php foreach (array('auto', 'cover', 'initial') as $size) { ?>
                    <option value="<?php echo $size; ?>" <?php echo ($this->data('theme.background_size') == $size) ? 'selected="selected"' : ''; ?>><?php echo $size; ?></option>
                  <?php } ?>
                  </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-background-repeat"><?php echo $entry_background_repeat; ?></label>
              <div class="col-md-2 col-sm-3">
                  <select name="theme[background_repeat]" id="input-background-repeat" class="form-control">
                  <?php foreach (array('repeat', 'no-repeat', 'fixed') as $repeat) { ?>
                    <option value="<?php echo $repeat; ?>" <?php echo ($this->data('theme.background_repeat') == $repeat) ? 'selected="selected"' : ''; ?>><?php echo $repeat; ?></option>
                  <?php } ?>
                  </select>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-custom-css"><?php echo $entry_custom_css; ?></label>
                <div class="col-sm-10">
                    <textarea name="theme[custom_css]" rows="5" id="input-custom-css" class="form-control"><?php echo $this->data('theme.custom_css'); ?></textarea>
                </div>
            </div>
          </fieldset>
                      </div>
                      <div class="tab-pane" id="tab_header">
        <fieldset>
            <legend><?php echo $text_header; ?></legend>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-category-menu"><?php echo $entry_category_menu; ?></label>
                <div class="col-md-2 col-sm-3">
                    <select name="theme[category_menu]" id="input-category-menu" class="form-control">
                    <?php foreach (array('button', 'inline') as $category_menu) { ?>
                        <option value="<?php echo $category_menu; ?>" <?php echo ($this->data('theme.category_menu') == $category_menu) ? 'selected="selected"' : ''; ?>><?php echo $category_menu; ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-sticky-logo"><?php echo $entry_sticky_logo; ?></label>
              <div class="col-md-2 col-sm-3">
                  <a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $sticky_logo_thumb; ?>" alt="" title="" /></a>
                  <input type="hidden" name="theme[sticky_logo]" value="<?php echo $this->data('theme.sticky_logo'); ?>" id="input-sticky-logo" />
                <?php if ($this->hasError('sticky_logo')) { ?>
                <div class="text-danger"><?php $this->error('sticky_logo'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-nav-type"><?php echo $entry_nav_type; ?></label>
                <div class="col-md-2 col-sm-3">
                    <select name="theme[nav_type]" id="input-nav-type" class="form-control">
                    <?php foreach (array('wide', 'boxed') as $nav_type) { ?>
                        <option value="<?php echo $nav_type; ?>" <?php echo ($this->data('theme.nav_type') == $nav_type) ? 'selected="selected"' : ''; ?>><?php echo $nav_type; ?></option>
                    <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="sections"><?php echo $entry_sections; ?></label>
                <div class="col-sm-10">
                        <table id="sections" class="table table-bordered menu_manager">
                        <thead>
                            <tr>
                                <td class="text-left" width="50%">Text</td>
                                <td class="text-left" width="50%">Href</td>
                                <td class="text-left" width="1"><?php echo $text_action; ?></td>
                            </tr>
                        </thead>

                        <?php
                        $section_row = 1;
                        if($this->data('theme.sections')) {
                          $first_set = $this->data('theme.sections');
                          $first_set = reset($first_set);
                        foreach ($first_set as $skey => $section) { ?>
                            <tbody id="tab-section-<?php echo $section_row; ?>" class="group">
                                <tr>
                                    <td class="text-left">
                                        <?php foreach ($languages as $language) { ?>
                                        <div class="input-group">
                                        <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                        <input type="text" class="form-control" name="theme[sections][<?php echo $language['language_id']; ?>][<?php echo $section_row; ?>][title]"
                                        value="<?php echo $this->data("theme.sections.{$language['language_id']}.{$skey}.title"); ?>" />
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <td class="text-left">
                                    <?php foreach ($languages as $language) { ?>
                                        <input type="text" class="form-control" name="theme[sections][<?php echo $language['language_id']; ?>][<?php echo $section_row; ?>][href]" value="<?php echo $this->data("theme.sections.{$language['language_id']}.{$skey}.href"); ?>" />
                                    <?php } ?>
                                    </td>

                                    <td>
                                        <a class="btn btn-danger" data-toggle="tooltip" data-original-title="<?php echo $button_remove; ?>" onclick="$('#tab-section-<?php echo $section_row; ?>').remove();"><i class="fa fa-trash"></i></a>
                                    </td>

                                </tr>
                            </tbody>
                            <?php
                            $section_row++;
                            }
                            }
                            ?>
                            <tfoot id="section_add">
                            <tr>
                                <td colspan="2"></td>
                                <td class="text-right">
                                <a class="btn btn-info" onclick="addSection();"><?php echo $text_add_section; ?></a>
                                </td>
                            </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
        </fieldset>
                      </div>
                      <div class="tab-pane" id="tab_footer">
          <fieldset>
            <legend><?php echo $text_footer; ?></legend>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-footer-type"><?php echo $entry_footer_template; ?></label>
                    <div class="col-sm-10">
                        <select name="theme[footer_template]" id="input-footer-type" class="form-control">
                            <?php foreach (array('template_1' => 'entry_footer_tpl_1', 'template_2' => 'entry_footer_tpl_2') as $template => $name) { ?>
                                <option value="<?php echo $template; ?>" <?php echo ($this->data('theme.footer_template') == $template) ? 'selected="selected"' : ''; ?>><?php echo $$name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="theme[email]" value="1" <?php if($this->data('theme.email')) { ?> checked="checked" <?php } ?> id="input-email" />
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-map"><?php echo $entry_social; ?></label>
                    <div class="col-sm-10">
                        <div class="panel panel-default social">
                            <div class="panel-heading"><b><?php echo $entry_fb; ?></b></div>
                            <div class="panel-body">
                                <input type="text" class="form-control" name="theme[social][fb]" value="<?php echo $this->data("theme.social.fb"); ?>" />
                            </div>
                            <div class="panel-heading"><b><?php echo $entry_google; ?></b></div>
                            <div class="panel-body">
                                <input type="text" class="form-control" name="theme[social][google]" value="<?php echo $this->data("theme.social.google"); ?>" />
                            </div>
                            <div class="panel-heading"><b><?php echo $entry_twitter; ?></b></div>
                            <div class="panel-body">
                                <input type="text" class="form-control" name="theme[social][twitter]" value="<?php echo $this->data("theme.social.twitter"); ?>" />
                            </div>
                            <div class="panel-heading"><b><?php echo $entry_instagram; ?></b></div>
                            <div class="panel-body">
                                <input type="text" class="form-control" name="theme[social][instagram]" value="<?php echo $this->data("theme.social.instagram"); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
               <div class="form-group" id="footer_about">
                <label class="col-sm-2 control-label" for="footer_about"><?php echo $entry_footer_about; ?></label>
                <div class="col-sm-10">
                    <table class="table table-bordered menu_manager">
                        <thead>
                            <tr>
                                <td class="text-left" width="40%"><?php echo $entry_title; ?></td>
                                <td class="text-left" width="60%"><?php echo $entry_text; ?></td>
                            </tr>
                        </thead>
                            <tbody class="group">
                                <tr>
                                    <td class="text-left">
                                        <?php foreach ($languages as $language) { ?>
                                        <div class="input-group">
                                        <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                        <input type="text" class="form-control" name="theme[footer_about][<?php echo $language['language_id']; ?>][title]"
                                        value="<?php echo $this->data("theme.footer_about.{$language['language_id']}.title"); ?>" />
                                        </div>
                                        <?php } ?>
                                    </td>
                                    <td class="text-left">
                                      <?php foreach ($languages as $language) { ?>
                                        <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                        <textarea name="theme[footer_about][<?php echo $language['language_id']; ?>][text]" rows="5" class="form-control"><?php echo $this->data("theme.footer_about.{$language['language_id']}.text"); ?></textarea>
                                      <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_footer_custom_html; ?></label>
                <div class="col-sm-10">
                    <?php foreach ($languages as $language) { ?>
                        <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                        <textarea name="theme[footer_custom_html][<?php echo $language['language_id']; ?>]" rows="5" class="form-control"><?php echo $this->data("theme.footer_custom_html.{$language['language_id']}"); ?></textarea>
                    <?php } ?>
                </div>
            </div>
          </fieldset>
                      </div>
                     <div class="tab-pane" id="tab_contact">
          <fieldset>
            <legend><?php echo $text_contact; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-map"><?php echo $entry_map; ?></label>
              <div class="col-sm-10">
                <select name="theme[map]" id="input-map" class="form-control">
                  <?php if ($this->data('theme.map')) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group" id="map-embed">
                <label class="col-sm-2 control-label" for="input-map-embed-url"><?php echo $entry_map_embed_url; ?></label>
                <div class="col-sm-10">
                    <textarea name="theme[map_embed_url]" rows="5" id="input-map-embed-url" class="form-control"><?php echo $this->data('theme.map_embed_url'); ?></textarea>
                </div>
            </div>
          </fieldset>
                     </div>
                     <div class="tab-pane" id="tab_product">
          <fieldset>
            <legend><?php echo $text_product; ?></legend>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-catalog-limit"><span data-toggle="tooltip" title="<?php echo $help_product_limit; ?>"><?php echo $entry_product_limit; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="theme[product_limit]" value="<?php echo $this->data('theme.product_limit'); ?>" placeholder="<?php echo $entry_product_limit; ?>" id="input-catalog-limit" class="form-control" />
                <?php if ($this->hasError('product_limit')) { ?>
                <div class="text-danger"><?php $this->error('product_limit'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-description-limit"><span data-toggle="tooltip" title="<?php echo $help_product_description_length; ?>"><?php echo $entry_product_description_length; ?></span></label>
              <div class="col-sm-10">
                <input type="text" name="theme[product_description_length]" value="<?php echo $this->data('theme.product_description_length'); ?>" placeholder="<?php echo $entry_product_description_length; ?>" id="input-description-limit" class="form-control" />
                <?php if ($this->hasError('product_description_length')) { ?>
                <div class="text-danger"><?php $this->error('product_description_length'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="warranty_information_id"><b><?php echo $entry_warranty_information; ?></b><br/><small><?php echo $entry_warranty_information_i; ?></small></label>
                <div class="col-sm-10">
                    <select class="form-control" name="theme[warranty_information_id]" id="warranty_information_id">
                        <option value="0"> - </option>
                        <?php foreach ($informations as $information) { ?>
                        <?php if ($information['information_id'] == $this->data('theme.warranty_information_id')) { ?>
                        <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sup-price"><span data-toggle="tooltip" title="<?php echo $help_currency_sup; ?>"><?php echo $entry_currency_sup; ?></span></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                          <input type="checkbox" name="theme[currency_sup]" value="1" <?php if($this->data('theme.currency_sup')) { ?> checked="checked" <?php } ?> id="input-sup-price" />
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-quickview"><?php echo $entry_quickview; ?></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="theme[quickview]" value="1" <?php if($this->data('theme.quickview')) { ?> checked="checked" <?php } ?> id="input-quickview" />
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-compare"><?php echo $entry_compare; ?></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="theme[compare]" value="1" <?php if($this->data('theme.compare')) { ?> checked="checked" <?php } ?> id="input-compare" />
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-wishlist"><?php echo $entry_wishlist; ?></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="theme[wishlist]" value="1" <?php if($this->data('theme.wishlist')) { ?> checked="checked" <?php } ?> id="input-wishlist" />
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="extra-tabs"><?php echo $entry_extra_tabs; ?></label>
              <div class="col-sm-10">
                      <table id="extra-tabs" class="table table-bordered menu_manager">
                      <thead>
                          <tr>
                              <td class="text-left" width="50%"><?php echo $text_name; ?></td>
                              <td class="text-left" width="50%"><?php echo $text_description; ?></td>
                              <td class="text-left" width="1"><?php echo $text_action; ?></td>
                          </tr>
                      </thead>

                      <?php
                      $tab_row = 1;
                      if($this->data('theme.extra_tabs')) {
                        $first_set = $this->data('theme.extra_tabs');
                        $first_set = reset($first_set);
                      foreach ($first_set as $tkey => $tab) { ?>
                          <tbody id="extra-tab-<?php echo $tab_row; ?>" class="group">
                              <tr>
                                  <td class="text-left">
                                      <?php foreach ($languages as $language) { ?>
                                      <div class="input-group">
                                      <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                      <input type="text" class="form-control" name="theme[extra_tabs][<?php echo $language['language_id']; ?>][<?php echo $tab_row; ?>][title]"
                                      value="<?php echo $this->data("theme.extra_tabs.{$language['language_id']}.{$tkey}.title"); ?>" />
                                      </div>
                                      <?php } ?>
                                  </td>
                                  <td class="text-left">
                                      <?php foreach ($languages as $language) { ?>
                                        <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                                        <textarea name="theme[extra_tabs][<?php echo $language['language_id']; ?>][<?php echo $tab_row; ?>][description]" rows="5" class="form-control"><?php echo $this->data("theme.extra_tabs.{$language['language_id']}.{$tkey}.description"); ?></textarea>
                                      <?php } ?>
                                  </td>

                                  <td>
                                      <a class="btn btn-danger" data-toggle="tooltip" data-original-title="<?php echo $button_remove; ?>" onclick="$('#extra-tab-<?php echo $tab_row; ?>').remove();"><i class="fa fa-trash"></i></a>
                                  </td>

                              </tr>
                          </tbody>
                          <?php
                          $tab_row++;
                          }
                          }
                          ?>
                          <tfoot id="tab_add">
                          <tr>
                              <td colspan="2"></td>
                              <td class="text-right">
                              <a class="btn btn-info" onclick="addTab();"><?php echo $text_add_tab; ?></a>
                              </td>
                          </tr>
                          </tfoot>
                  </table>
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sales-agents"><?php echo $entry_sales_agents; ?></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="theme[show_sales_agents]" value="1" <?php if($this->data('theme.show_sales_agents')) { ?> checked="checked" <?php } ?> id="input-sales-agents" />
                        </label>
                    </div>
                </div>
            </div>
            <div id="sales-agents-group" class="form-group <?php if(!$this->data('theme.show_sales_agents')) { ?>hidden<?php } ?>">
                <label class="col-sm-2 control-label"><?php echo $text_sales_agents; ?></label>
                <div class="col-sm-10">
                    <table id="contact-sections" class="table table-bordered menu_manager">
                        <thead>
                            <tr>
                                <td class="text-left"><?php echo $entry_contact_name; ?></td>
                                <td class="text-left"><?php echo $entry_store_phone; ?></td>
                                <td class="text-left"><?php echo $entry_email_address; ?></td>
                                <td class="text-left" width="1"><?php echo $text_action; ?></td>
                            </tr>
                        </thead>
                        <?php
                        $section_sales_agent_row = 1;
                        if($this->data('theme.sales_agents')) {
                        foreach ($this->data('theme.sales_agents') as $ckey => $contact) { ?>
                                  <tbody id="tab-sales-agents-section-<?php echo $section_sales_agent_row; ?>" class="group">
                              <tr>
                                  <td class="text-left">
                                      <input type="text" class="form-control" name="theme[sales_agents][<?php echo $section_sales_agent_row; ?>][name]"
                                      value="<?php echo $contact['name']; ?>" />
                                  </td>
                                  <td class="text-left">
                                      <input type="text" class="form-control" name="theme[sales_agents][<?php echo $section_sales_agent_row; ?>][phone]"
                                      value="<?php echo $contact['phone']; ?>" />
                                  </td>
                                  <td class="text-left">
                                      <input type="text" class="form-control" name="theme[sales_agents][<?php echo $section_sales_agent_row; ?>][email]"
                                      value="<?php echo $contact['email']; ?>" />
                                  </td>
                                  <td>
                                      <a class="btn btn-danger" data-toggle="tooltip" data-original-title="<?php echo $button_remove; ?>" onclick="$('#tab-sales-agents-section-<?php echo $section_sales_agent_row; ?>').remove();"><i class="fa fa-trash"></i></a>
                                  </td>
                              </tr>
                            </tbody>
                            <?php $section_sales_agent_row++; ?>
                            <?php } ?>
                            <?php } ?>
                            <tfoot>
                            <tr>
                                <td colspan="3"></td>
                                <td class="text-right">
                                  <a class="btn btn-info" onclick="addSalesAgentSection(this);"><?php echo $text_add_contact_section; ?></a>
                                </td>
                            </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
          </fieldset>
                     </div>
                    <div class="tab-pane" id="tab_image">
          <fieldset>
            <legend><?php echo $text_image; ?></legend>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-category-width"><?php echo $entry_image_category; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_category_width]" value="<?php echo $this->data('theme.image_category_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-category-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_category_height]" value="<?php echo $this->data('theme.image_category_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_category')) { ?>
                <div class="text-danger"><?php $this->error('image_category'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-thumb-width"><?php echo $entry_image_thumb; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_thumb_width]" value="<?php echo $this->data('theme.image_thumb_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-thumb-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_thumb_height]" value="<?php echo $this->data('theme.image_thumb_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_thumb')) { ?>
                <div class="text-danger"><?php $this->error('image_thumb'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-popup-width"><?php echo $entry_image_popup; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_popup_width]" value="<?php echo $this->data('theme.image_popup_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-popup-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_popup_height]" value="<?php echo $this->data('theme.image_popup_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_popup')) { ?>
                <div class="text-danger"><?php $this->error('image_popup'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-product-width"><?php echo $entry_image_product; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_product_width]" value="<?php echo $this->data('theme.image_product_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-product-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_product_height]" value="<?php echo $this->data('theme.image_product_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_product')) { ?>
                <div class="text-danger"><?php $this->error('image_product'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-additional-width"><?php echo $entry_image_additional; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_additional_width]" value="<?php echo $this->data('theme.image_additional_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-additional-width" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_additional_height]" value="<?php echo $this->data('theme.image_additional_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_additional')) { ?>
                <div class="text-danger"><?php $this->error('image_additional'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-related"><?php echo $entry_image_related; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_related_width]" value="<?php echo $this->data('theme.image_related_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-related" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_related_height]" value="<?php echo $this->data('theme.image_related_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_related')) { ?>
                <div class="text-danger"><?php $this->error('image_related'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-compare"><?php echo $entry_image_compare; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_compare_width]" value="<?php echo $this->data('theme.image_compare_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-compare" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_compare_height]" value="<?php echo $this->data('theme.image_compare_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_compare')) { ?>
                <div class="text-danger"><?php $this->error('image_compare'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-wishlist"><?php echo $entry_image_wishlist; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_wishlist_width]" value="<?php echo $this->data('theme.image_wishlist_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-wishlist" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_wishlist_height]" value="<?php echo $this->data('theme.image_wishlist_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_wishlist')) { ?>
                <div class="text-danger"><?php $this->error('image_wishlist'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-cart"><?php echo $entry_image_cart; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_cart_width]" value="<?php echo $this->data('theme.image_cart_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-cart" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_cart_height]" value="<?php echo $this->data('theme.image_cart_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_cart')) { ?>
                <div class="text-danger"><?php $this->error('image_cart'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
              <label class="col-sm-2 control-label" for="input-image-location"><?php echo $entry_image_location; ?></label>
              <div class="col-sm-10">
                <div class="row">
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_location_width]" value="<?php echo $this->data('theme.image_location_width'); ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-location" class="form-control" />
                  </div>
                  <div class="col-sm-6">
                    <input type="text" name="theme[image_location_height]" value="<?php echo $this->data('theme.image_location_height'); ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                  </div>
                </div>
                <?php if ($this->hasError('image_location')) { ?>
                <div class="text-danger"><?php $this->error('image_location'); ?></div>
                <?php } ?>
              </div>
            </div>
            <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-image-quality"><?php echo $entry_image_quality; ?></label>
                <div class="col-sm-10">
                    <div class="row">
                    <div class="col-sm-6">
                        <input type="text" name="theme[image_quality]" value="<?php echo $this->data('theme.image_quality', 90); ?>" placeholder="<?php echo $entry_quality; ?>" id="input-image-quality" class="form-control" />
                    </div>
                    </div>
                    <?php if ($this->hasError('image_quality')) { ?>
                    <div class="text-danger"><?php $this->error('image_quality'); ?></div>
                    <?php } ?>
                </div>
            </div>
          </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
        <!-- Import Modal Start-->
        <div class="modal fade" id="import-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $text_import; ?></h4>
                </div>
                <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" id="form-import">
                    <div class="modal-body">                    
                            <label class="col-sm-5 control-label" for="input-import-settings"><?php echo $entry_import_settings; ?></label>
                            <div class="col-sm-7">
                                <label>
                                    <input type="checkbox" name="import_settings" value="1" checked disabled id="input-import-settings" />
                                </label>
                            </div>
                            <label class="col-sm-5 control-label" for="input-import-extensions"><?php echo $entry_import_extensions; ?></label>
                            <div class="col-sm-7">
                                <label>
                                    <input type="checkbox" name="import_extensions" value="1" id="input-import-extensions" />
                                </label>
                            </div>
                            <input type="file" name="file" id="file-name" />                  
                        <h4 class="text-danger text-center import-h4"><?php echo $entry_info_import; ?></h4>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $text_close; ?></button>
                    <input type="submit" id="submit-import" class="btn btn-primary" value="<?php echo $text_import; ?>" />
                </form>
                </div>
            </div>
        </div>
        <!-- Import Modal End-->
  </div>
</div>
<style>
    /* Tabs for Extension -> Theme -> theme-default */
    .tabs-left-default {
        border-bottom: none;
        padding-top: 2px;
        height:100%;
      }
      .tabs-left-default {
        border-right: 1px solid #ddd;
      }
      .tabs-left-default>li {
        float: none;
        margin-bottom: 2px;
      }
      .tabs-left-default>li {
        margin-right: -1px;
      }
      .tabs-left-default>li.active>a,
      .tabs-left-default>li.active>a:hover,
      .tabs-left-default>li.active>a:focus {
        border-bottom-color: #ddd;
        border-right-color: transparent;
      }
      .tabs-left-default>li>a {
        border-radius: 4px 0 0 4px;
        margin-right: 0;
        display:block;
      }
      .tabs-left-default {
        left: -50px;
      }

      .btn-import, .btn-export{
        background-color: #12bb7b;
      }
      
      .btn-export{
        background-color: #e29426;
      }
      
      .import-h4 {
          margin-top: 30px;
      }

      .modal .alert.alert-danger,
      .modal .alert.alert-success {
          padding-left: 30px;
      }

      .upload-label {
          margin-top: 15px;
          padding-left: 15px;
      }

      input[type=file] {
          padding-left: 15px;
          margin-top: 75px;
      }

      /* Tabs for Extension -> Theme -> theme-default */
</style>
<script src="<?php echo HTTPS_CATALOG; ?>system/awebcore/admin/views/javascript/colorpicker/jscolor.min.js"></script>
<?php echo $footer; ?>

<script type="text/javascript">
$('#import-modal').on('hidden.bs.modal', function(e) {
    $(this).find('form').trigger('reset');
    $("div.alert.alert-danger").remove( "div.alert.alert-danger" );
    $("div.alert.alert-success").remove( "div.alert.alert-success" );
})

    $('#form-import').on('submit', function(e) {
        e.preventDefault(e);
        $('.alert').remove();
        if ($('#form-import input[name=\'file\']').val() != '') {                        
            $.ajax({
                url: 'index.php?route=extension/theme/awebcore/import&<?php echo (isOc3()) ? "user_token" : "token"; ?>=<?php echo (isOc3()) ? $user_token : $token; ?>',
                type: 'post',
                dataType: 'json',
                data: new FormData($('#form-import')[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#submit-import').button('loading');
                },
                complete: function() {
                    $('#submit-import').button('reset');
                },
                success: function(json) {
                    if (json['error']) {                    
                        $('#form-import').prepend('<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a>' + json['error'] + '</div>');
                    } else {
                        $('#form-import').prepend('<div class="alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a>' + json['success'] + '</div>');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        } else {
            $('#form-import').prepend('<div class="alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><strong><?php echo $error_file_missing; ?></strong></div>');
        }

        return false;
    });
    //import settings end

  var section_row = <?php echo $section_row; ?>;

function addSection() {
	group_row = 0;
   	html  = '<tbody id="tab-section-' + section_row + '">';
	html += '<tr>';
	html += '<td>';
	<?php foreach ($languages as $language) { ?>
	html += '<div class="input-group">';
    html += '<span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>';
    html += '<input type="text" class="form-control" name="theme[sections][<?php echo $language['language_id']; ?>][' + section_row + '][title]" />';
	html += '</div>';
	<?php } ?>
	html += '</td>';
	html += '<td>';
    <?php foreach ($languages as $language) { ?>
    html += '<input type="text" class="form-control" name="theme[sections][<?php echo $language['language_id']; ?>][' + section_row + '][href]" />';
    <?php } ?>
	html += '</td>';
	html += '<td class="text-left"><a class="btn btn-danger" rel="tooltip" title="<?php echo $button_remove; ?>" onclick="$(\'#tab-section-' + section_row + '\').remove();"><i class="fa fa-trash"></i></a> </td>';


	html += '</tr>';
	html += '</tbody>';

	html += '<div id="group-holder-' + section_row + '"></div>';

	$('#section_add').before(html);
	$('[rel=tooltip]').tooltip();
	section_row++;

}

$('#section li:first-child a').tab('show');
</script>

<script type="text/javascript">
<?php
  $section_row = 1;
  $group_row = 0;
  if($this->data('theme.sections')) {
  foreach ($this->data('theme.sections') as $section) { ?>
    $('#language<?php echo $section_row; ?> li:first-child a').tab('show');
  <?php
  $section_row++;
  }
}
?>

$(document).ready(function(){
  $('#store_id').on('change', function(){
    window.location = $(this).val();
  });

  if($("#input-map").val() == 0){
      $('#map-embed').hide();
  }
  $("#input-map").change(function() {
      if($(this).val() == '1'){
          $('#map-embed').show();
      } else {
          $('#map-embed').hide();
      }
  });
});

var section_sales_agent_row = <?php echo $section_sales_agent_row; ?>;

function addSalesAgentSection(el) {

   	html = '<tbody id="tab-sales-agents-section-' + section_sales_agent_row + '" class="group">';
     html  += '<tr>';
	html += '<td>';
    html += '<input type="text" class="form-control" name="theme[sales_agents][' + section_sales_agent_row + '][name]" />';
	html += '</td>';
	html += '<td>';
    html += '<input type="text" class="form-control" name="theme[sales_agents][' + section_sales_agent_row + '][phone]" />';
	html += '</td>';
	html += '<td>';
    html += '<input type="text" class="form-control" name="theme[sales_agents][' + section_sales_agent_row + '][email]" />';
	html += '</td>';
	html += '<td class="text-left"><a class="btn btn-danger" rel="tooltip" title="<?php echo $button_remove; ?>" onclick="$(\'#tab-sales-agents-section-' + section_sales_agent_row + '\').remove();"><i class="fa fa-trash"></i></a> </td>';
	html += '</tr>';
  html += '</tbody>';

	$(el).parents('tfoot').before(html);
	$('[rel=tooltip]').tooltip();
	section_sales_agent_row++;
}

$('#input-sales-agents').change(function(){
  if($(this).is(":checked")) {
    $('#sales-agents-group').removeClass('hidden');
  } else {
    $('#sales-agents-group').addClass('hidden');
  }
})
</script>

<script type="text/javascript">
  var tab_row = <?php echo $tab_row; ?>;

  function addTab() {
    group_row = 0;
      html  = '<tbody id="extra-tab-' + tab_row + '">';
    html += '<tr>';
    html += '<td>';
    <?php foreach ($languages as $language) { ?>
    html += '<div class="input-group">';
      html += '<span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>';
      html += '<input type="text" class="form-control" name="theme[extra_tabs][<?php echo $language['language_id']; ?>][' + tab_row + '][title]" />';
    html += '</div>';
    <?php } ?>
    html += '</td>';
    html += '<td>';
      <?php foreach ($languages as $language) { ?>
      html +='<span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>';
      html +='<textarea name="theme[extra_tabs][<?php echo $language['language_id']; ?>][' + tab_row + '][description]" rows="5" class="form-control"></textarea>';
      <?php } ?>
    html += '</td>';
    html += '<td class="text-left"><a class="btn btn-danger" rel="tooltip" title="<?php echo $button_remove; ?>" onclick="$(\'#extra-tab-' + tab_row + '\').remove();"><i class="fa fa-trash"></i></a> </td>';


    html += '</tr>';
    html += '</tbody>';

    html += '<div id="group-tabs-holder-' + tab_row + '"></div>';

    $('#tab_add').before(html);
    $('[rel=tooltip]').tooltip();
    tab_row++;
  }
</script>