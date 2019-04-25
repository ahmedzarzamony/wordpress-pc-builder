<div class="pcbuilder_gen pcbuilder_sgen">
    <button type="button" data-name="pcbuilder_spec" class="button button-primary button-large">Add New Spec</button>

    <div class="pcbuilder_genco" id="sortable">
        <?php if(!empty($data)){ ?>
        <?php $i=0; foreach($data as $k=>$d){ ?>
            <div>
        <div class="pcg_item">
            <i>&times;</i>
            <input type="text" placeholder="Title" name="pcbuilder_spec[<?= $i ?>][title]" value="<?= $k ?>">
            <textarea placeholder="Content" name="pcbuilder_spec[<?= $i ?>][content]"><?= $d ?></textarea>
        </div><!-- pcg_item -->
                </div>
        <?php $i++;}} ?>
    </div><!-- pcbuilder_gen -->

    <div class="pcbuilder_store">
        <div class="pcg_item">
            <i>&times;</i>
            <input type="text" placeholder="Title"><br>
            <textarea placeholder="Content" ></textarea>
        </div><!-- pcg_item -->
    </div><!-- pcbuilder_store -->
</div><!-- pcbuilder_gen -->

