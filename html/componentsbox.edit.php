<tr class="form-field term-parent-wrap">
<th scope="row"><label for="parent">Parent Component</label></th>
<td>
<select name="component_type">
<option value="">None</option>
<?php
    if(isset($pcbuilder_groups)){
        foreach($pcbuilder_groups as $key => $group){
            echo '<option '.(@$component_type == $key ? 'selected' : '').' value="'.$key.'">'.$group.'</option>';
        }
    }
?>
</select>
</td>
</tr>