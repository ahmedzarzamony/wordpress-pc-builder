<tr class="form-field term-parent-wrap">
<th scope="row"><label for="parent">Parent Component</label></th>
<td>
<select name="component_type">
<option value="-1">None</option>
<option <?= @$component_type == 'CPU' ? 'selected' : 'CPU' ?> value="CPU">CPU</option>
<option <?= @$component_type == 'GPU' ? 'selected' : 'GPU' ?> value="GPU">GPU</option>
<option <?= @$component_type == 'RAM' ? 'selected' : 'RAM' ?> value="RAM">RAM</option>
<option <?= @$component_type == 'MB' ? 'selected' : 'MB' ?> value="MB">MB</option>
<option <?= @$component_type == 'HDD' ? 'selected' : 'HDD' ?> value="HDD">HDD</option>
<option <?= @$component_type == 'PSU' ? 'selected' : 'PSU' ?> value="PSU">PSU</option>

</select>
</td>
</tr>