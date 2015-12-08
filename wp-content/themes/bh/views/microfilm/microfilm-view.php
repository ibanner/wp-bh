<div id="microfilm-form" ng-app="microfilmApp" ng-controller="mfController as ctrl" ng-init="getMicrofilmData()">
    <div id="description">
        <?php the_content(); ?>
    </div>
    <div ng-hide="show_payment">
    	<div class="instructions">
	        <div class="inline bullet">1</div>
	        <div style="display: inline-block; vertical-align: top;">
	            <div><?php _e('Select a microfilm', 'BH'); ?></div>
	            <div><?php _e('Click', 'BH'); ?><div style="display: inline-block" class="green-button"><?php _e('Add', 'BH'); ?></div><?php _e('to add a microfilm to the list', 'BH'); ?>.</div>
	        </div>       
	    </div>
	    <div id="letter-container">
	        <div class="letter" ng-repeat="letter in letters" ng-class="{'selected-letter': currentLetter === letter}" ng-click="currentLetter = letter">{{letter}}</div>
	    </div>
	    <table id="microfilms">
	        <thead>
	            <tr>
	                <th class="town"><?php _e('Town', 'BH'); ?></th>
	                <th class="province"><?php _e('Province', 'BH'); ?></th>
	                <th class="type"><?php _e('Type', 'BH'); ?></th>
	                <th class="years"><?php _e('Years Covered', 'BH'); ?></th>
	                <th class="microfilm"><?php _e('Microfilm', 'BH'); ?></th>
	            </tr>
	        </thead>
	        <tbody>
	            <tr ng-class="{'selected-row': findMatch(data)}" ng-repeat="data in microfilmData | sortByLetter: currentLetter">
	                <td>{{data.Town}}</td>
	                <td>{{data.Province}}</td>
	                <td>{{data.Type}}</td>
	                <td>{{data.YearsCovered}}</td>
	                <td>
	                    {{data.Microfilm}}
	                    <div class="button green-button" ng-show="!findMatch(data)" ng-click="addMicrofilm(data)"><?php _e('Add', 'BH'); ?></div>
	                    <div class="button green-button remove" ng-show="findMatch(data)" ng-click="removeMicrofilm(data.Microfilm)"><?php _e('Remove', 'BH'); ?></div>
	                </td>
	            </tr>
	        </tbody>
	    </table>
	    <form name="form" class="bh-form" rc-submit="submitForm()" novalidate>
	        <input name="password" class="out" tabindex="-1" ng-model="senderDetails.password"/>
	        <div id="selected-wrapper">
	            <table id="selected">
	                <thead ng-show="microfilms.length > 0">
	                    <tr>
	                        <th class="placeholder"></th>
	                        <th class="microfilm"><?php _e('*Microflim', 'BH') ?></th>
	                        <th class="type">*<?php _e('Type', 'BH') ?></th>
	                        <th class="year">*<?php _e('Year', 'BH') ?></th>
	                        <th class="act">*<?php _e('Act', 'BH') ?></th>
	                        <th class="town">*<?php _e('Town', 'BH') ?></th>
	                        <th class="fname">*<?php _e('Family Name', 'BH') ?></th>
	                        <th class="gname">*<?php _e('Given Name', 'BH') ?></th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <tr ng-repeat="microfilm in microfilms">
	                        <td class="minus-container"><div class="minus button" ng-click="removeMicrofilm(microfilm.data.Microfilm)">-</div></td>
	                        <td class="microfilm">
	                            <input ng-model="microfilm.data.Microfilm" ng-change="changeMicrofilm(microfilm)"/>
	                            <div class="error" ng-class="{'semi-error': microfilm.data.Microfilm.length > 0}" ng-hide="microfilm.valid"><?php _e('Please enter valid microfilm', 'BH') ?></div>
	                        </td>
	                        <td class="type">
	                            <select ng-model="microfilm.data.chosenType" ng-options="type for type in microfilm.data.Type.split('')" required /></select>
	                            <div class="error" ng-show="rc.form.attempted && (!microfilm.data.chosenType || microfilm.data.chosenType === '')"><?php _e('Required', 'BH') ?></div>
	                        </td>
	                        <td class="years">
	                            <input ng-model="microfilm.data.Year" required />
	                            <div class="error" ng-show="rc.form.attempted && (!microfilm.data.Year || microfilm.data.Year === '')"><?php _e('Required', 'BH') ?></div>
	                        </td>
	                        <td class="act">
	                            <input ng-model="microfilm.data.Act" required />
	                            <div class="error" ng-show="rc.form.attempted && (!microfilm.data.Act || microfilm.data.Act === '')"><?php _e('Required', 'BH') ?></div>
	                        </td>
	                        <td class="town">
	                            <input ng-model="microfilm.data.Town" />
	                            <div class="error" ng-show="rc.form.attempted && (!microfilm.data.Town || microfilm.data.Town === '')"><?php _e('Required', 'BH') ?></div>
	                        </td>
	                        <td class="fname">
	                            <input ng-model="microfilm.data.FamilyName" required />
	                            <div class="error" ng-show="rc.form.attempted && (!microfilm.data.FamilyName || microfilm.data.FamilyName === '')"><?php _e('Required', 'BH') ?></div>
	                        </td>
	                        <td class="gname">
	                            <input ng-model="microfilm.data.GivenName" required />
	                            <div class="error" ng-show="rc.form.attempted && (!microfilm.data.GivenName || microfilm.data.GivenName === '')"><?php _e('Required', 'BH') ?></div>
	                        </td>
	                    </tr>
	                </tbody>
	            </table>
	            <div class="plus button" ng-click="addMicrofilm()">+</div><div class="inline"><?php _e('Add another microfilm', 'BH') ?></div>
	        </div>
	        <div class="instructions top-border">
	            <div class="inline bullet">2</div>
	            <div class="inline"><?php _e('Your details', 'BH') ?></div>
	        </div>
	        <table id="details">
	            <tr>
	                <td>
	                    <label for="fullname">*<?php _e('Full Name', 'BH') ?></label>
	                    <input name="fullname" id="fullname" ng-model="senderDetails.fullname" required />
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.fullname), 'validation-icon-valid': !rc.form.needsAttention(form.fullname) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.fullname)"><?php _e('Required', 'BH') ?></div>
	                </td>
	                <td>
	                    <label for="institution"><?php _e('Institution (if applicable)', 'BH') ?></label>
	                    <input id="institution"ng-model="senderDetails.institution" />
	                </td>
	            </tr>
	            <tr>
	                <td>
	                    <label for="adress">*<?php _e('Address', 'BH') ?></label>
	                    <input name="address" id="adress" ng-model="senderDetails.address" required />
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.address), 'validation-icon-valid': !rc.form.needsAttention(form.address) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.address)"><?php _e('Required', 'BH') ?></div>
	                </td>
	                <td>
	                    <label for="city">*<?php _e('City', 'BH') ?></label>
	                    <input name="city" id="city" ng-model="senderDetails.city" required />
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.city), 'validation-icon-valid': !rc.form.needsAttention(form.city) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.city)"><?php _e('Required', 'BH') ?></div>
	                </td>
	            </tr>
	             <tr>
	                <td>
	                    <label for="country">*<?php _e('Country', 'BH') ?></label>
	                    <select name="country" id="country" ng-model="senderDetails.country" ng-include="templates.countryList" required ></select>
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.country), 'validation-icon-valid': !rc.form.needsAttention(form.country) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.country)"><?php _e('Required', 'BH') ?></div>
	                </td>
	                <td>
	                    <label for="zipcode">*<?php _e('Zip / Postal code', 'BH') ?></label>
	                    <input name="zipcode" id="zipcode" ng-model="senderDetails.zipcode" required />
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.zipcode), 'validation-icon-valid': !rc.form.needsAttention(form.zipcode) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.zipcode)"><?php _e('Required', 'BH') ?></div>
	                </td>
	            </tr>
	            <tr>
	                <td ng-show="senderDetails.country === 'United States of America'">
	                    <label for="state">*<?php _e('State', 'BH') ?></label>
	                    <select name="state" id="state" ng-required="senderDetails.country === 'United States of America'" ng-model="senderDetails.state" ng-include="templates.stateList"></select>
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.state), 'validation-icon-valid': !rc.form.needsAttention(form.state) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.state)"><?php _e('Required', 'BH') ?></div>
	                </td>
	                <td ng-show="senderDetails.country === 'Canada'">
	                    <label for="province">*<?php _e('Province', 'BH') ?></label>
	                    <select name="province" id="province" ng-required="senderDetails.country === 'Canada'" ng-model="senderDetails.province" ng-include="templates.provinceList"></select>
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.province), 'validation-icon-valid': !rc.form.needsAttention(form.province) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.province)"><?php _e('Required', 'BH') ?></div>
	                </td>
	            </tr>
	            <tr>
	                <td>
	                    <label for="phone">*<?php _e('Phone', 'BH') ?></label>
	                    <div class="addphone-button plus button" add-phone="addphone-container">+</div>
	                    <input name="phone" id="phone" ng-model="senderDetails.phone" required />
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.phone), 'validation-icon-valid': !rc.form.needsAttention(form.phone) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.phone)"><?php _e('Required', 'BH') ?></div>
	                </td>
	                <td>
	                    <label form="email">*<?php _e('Email', 'BH') ?></label>
	                    <input type="email" name="email" id="email" ng-model="senderDetails.email" required />
	                    <div ng-class="{'validation-icon-invalid': rc.form.needsAttention(form.email), 'validation-icon-valid': !rc.form.needsAttention(form.email) && rc.form.attempted}"></div>
	                    <div class="error" ng-show="rc.form.needsAttention(form.email)"><?php _e('Please enter a valid email', 'BH') ?></div>
	                </td>
	            </tr>
	            <tr id="addphone-container" style="display: none;">
	                <td>
	                    <label for="phone"><?php _e('Additional Phone', 'BH') ?></label>
	                    <div class="addphone-button plus button" remove-phone="addphone-container" show-button="addphone-button">-</div>
	                    <input name="addphone" id="addphone" ng-model="senderDetails.addphone" />
	                </td>
	            </tr>
	        </table>
			
	        <input class="send" type="submit" value="<?php _e('Send', 'BH') ?>">
	        <img src="<?php echo get_bloginfo('template_directory') . '/images/general/loader.gif'; ?>" ng-show="submitInProgress" />
	        <div>
	            <div class="error" ng-show="rc.form.attempted && form.$invalid"><?php _e('There were validation errors. Please check form fields.', 'BH') ?></div>
	            <div class="error" ng-show="rc.form.attempted && microfilms.length == 0"><?php _e('You have not picked any microfilms.', 'BH') ?></div>
	            <div class="error" ng-show="rc.form.attempted && !validateMicrofilms()"><?php _e('You have picked invalid microfilms.', 'BH') ?></div>
	
	            <div class="error" ng-show="error.code == 0 || error.code == 2"><?php _e('We have expirienced a technical problem. Please try again later.', 'BH') ?></div>
	
	            <div ng-show="success && !rc.form.attempted"><?php _e('Form submited succesfuly. Thank you.', 'BH') ?></div>
	        </div>
	    </form>
	</div>
	
	<?php
		$price					= get_field('acf-options_microfilm_price', 'option');
		$transactionID_prefix	= get_field('acf-options_microfilm_transaction_id_prefix', 'option');
		
		if ( ! $price || ! $transactionID_prefix )
			return;
		
		$transactionID			= BH_generate_transactionID($transactionID_prefix);
		
		// store payment data for further processing via Pelecard iframe
		session_start();
		$_SESSION['payment_data']					= array();
		$_SESSION['payment_data']['goodUrl']		= TEMPLATE . '/functions/pelecard/payment-result.php';
		$_SESSION['payment_data']['errorUrl']		= TEMPLATE . '/functions/pelecard/payment-result.php';
		$_SESSION['payment_data']['total']			= $price * 100;
		$_SESSION['payment_data']['currency']		= '1';
		$_SESSION['payment_data']['transactionID']	= $transactionID;

		$pelecard_iframe = BH_pelecard_iframe(ICL_LANGUAGE_CODE);

		if ($pelecard_iframe) : ?>

			<div id="payment-form" ng-show="show_payment">
				<iframe id="frame" name="pelecard_frame" frameborder="0" scrolling="no" src="<?php echo $pelecard_iframe; ?>" style="width:100%; height:694px;"></iframe>
			</div>

		<?php endif; ?>
</div>

<?php

	wp_enqueue_script('angular');
	wp_enqueue_script('rcSubmit');
	wp_enqueue_script('microfilm');

?>