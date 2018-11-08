    <div class="">
        <div class="">
            <h1>{$page_data.page_title}</h1>
        </div>

        <div>
            <div>
                <!-- Table -->
                <table id="sys_reward" class="display dataTable table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
			{foreach from=$columns item=name}
                        	<th><h4>{$name}</h4></th>
			{/foreach}

                    </tr>
                    </thead>
		    {foreach name=outer from=$results item=result}
                    <tr id="{$result.reward_id}">
			{foreach key=key item=value from=$result}
			        <td id={$key}>{$value}</td>
			{/foreach}
                    </tr>
	            {/foreach}
                </table>

		<div class="add_delete_toolbar"></div>
 
		<form id="formAddNewRow" action="#" title="Add new record">
		  <table>
		   <tr><td>
                   <label for="name">Reward type</td><td></label>
                        <select name="reward_source_id" id="reward_source" rel="1">
				<option value="0">APPREWARDER</option>
				<option value="1">AMAZON</option>
				<option value="2">ITUNES</option>
				<option value="3">PAYPAL</option>
				<option value="4">BITCOIN</option>
			</select>
                   </td></tr>
		   <tr><td>
                    <label for="name">Reward name</td><td></label><input name="reward_name" id="reward_name" rel="2"/>
                   </td></tr>
		   <tr><td>
                    <label for="name">Reward description</td><td></label><input name="reward_description" id="reward_description" rel="3"/>
		   <tr><td>
                    <label for="name">Reward status</td><td></label><select name="reward_status" id="reward_status" rel="4">
									<option value='1'>enabled</option>
									<option value='0'>disabled</option>
									<option value='2'>sold out</option>
								     </select>
                   </td></tr>
		   <tr><td>
                    <label for="name">Reward payout (in real currency)</td><td></label><input name="reward_payout" id="reward_payout" rel="5"/>
                   </td></tr>
		   <tr><td>
                    <label for="name">Reward region</td><td></label><input name="reward_region" id="reward_region" value="US,INT" rel="6"/>
                   </td></tr>
		   <tr><td>
                    <label for="name">Reward image</td><td></label><input name="reward_img id="reward_img" rel="7"/>
                   </td></tr>
		   <tr><td>
                    <label for="name">Reward cost (in credits)</td><td></label><input name="reward_cost" id="reward_cost" rel="8"/>
                   </td></tr>
		   <tr><td>
                    <label for="name">Reward Expiration(in INT(11) TIMESTAMP)</td><td></label><input name="reward_expiration" id="reward_expiration" rel="9"/>
                   </td></tr>
		   <input type="hidden" name="junk1" value="nothing" rel="10"/>
		   <input type="hidden" name="junk2" value="nothing" rel="11"/>
		   <input type="hidden" name="junk3" value="nothing" rel="12"/>


		</table>
                </form>







            </div>
        </div>
    </div>
