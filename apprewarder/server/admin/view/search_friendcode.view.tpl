    <div class="">
        <div class="">
            <h1>{$page_data.page_title}</h1>
        </div>

        <div>
            <div>

                <div>

		<form name="input" action="/search_friendcode/submitfriendcode" method="get">
		Friend Code: <input type="text" name="friendcode">
		<input type="submit" value="Submit">
		</form>
		<BR><BR>
		</div>

                <!-- Table -->
                <table id="search_friendcode" class="display dataTable table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
			{foreach from=$columns item=name}
                        	<th><h4>{$name}</h4></th>
			{/foreach}

                    </tr>
                    </thead>
		    {foreach name=outer from=$results item=result}
                    <tr id="{$result.user_id}">
			{foreach key=key item=value from=$result}
			        <td id={$key}>{$value}</td>
			{/foreach}
                    </tr>
	            {/foreach}
                </table>
            </div>
        </div>
    </div>
