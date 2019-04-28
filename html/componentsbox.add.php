<div class="form-field term-parent-wrap">
	<label for="parent">Type</label>
    <select name="component_type">
        <option value="">None</option>
        <?php
            if(isset($pcbuilder_groups)){
                foreach($pcbuilder_groups as $key => $group){
                    echo '<option value="'.$key.'">'.$group.'</option>';
                }
            }
        ?>
    </select>
</div>
