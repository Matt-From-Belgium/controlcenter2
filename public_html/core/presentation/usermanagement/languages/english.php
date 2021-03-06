<?php
###USERMANAGEMENT LANGUAGE FILE: English

###HEADERS
define('LANG_CHANGE_PASSWORD',"Change your password");

##FORM LABELS
define('LANG_CREATE_NEW_ACCOUNT',"Create new account");
define('LANG_EDIT_ACCOUNT',"Edit useraccount");
define('LANG_USERNAME',"Username");
define('LANG_MAIL',"E-mail");
define('LANG_PASSWORD',"Password");
define('LANG_PASSWORD_CONFIRM',"Confirm your password");
define('LANG_FIRSTNAME',"Firstname");
define('LANG_LASTNAME',"Lastname");
define('LANG_WEBSITE',"Website");
define('LANG_COUNTRY',"Country");
define('LANG_SELECT_COUNTRY',"Select your country...");
define('LANG_ADD_USERGROUP',"Add Usergroup");
define('LANG_USERGROUP_NAME',"Usergroup name");
define('LANG_USERGROUP_MEMBERSHIP',"Usergroup membership");
define('LANG_LOGIN',"Aanmelden");
define('LANG_PASSWORD_CHANGE_REQUIRED','User must change password');
define('LANG_OLD_PASSWORD',"Current password");
define('LANG_NEW_PASSWORD1',"New password");
define('LANG_NEW_PASSWORD2',"Repeat password");

#ERROR MESSAGES
define('LANG_ERROR_USERNAME_EXISTS',"That username exists, choose a different one");
define('LANG_ERROR_USERNAME_EMPTY',"You must provide a username");
define('LANG_ERROR_MAIL_EMPTY',"You must provide your mailadress");
define('LANG_ERROR_PASSWORD_EMPTY',"You must provide a password");
define('LANG_ERROR_FIRSTNAME_EMPTY',"You must provide your firstname");
define('LANG_ERROR_LASTNAME_EMPTY',"You must provide your lastname");
define('LANG_ERROR_COUNTRY_EMPTY',"Please select a country");
define('LANG_ERROR_PASSWORDMATCH',"Passwords don't match");
define('LANG_ERROR_MAILADRESS_EXISTS',"There already is a user with that mailadress");
define('LANG_ERROR_WRONGLOGIN',"Invalid username and/or password");
define('LANG_ERROR_TOOMANYATTEMPTS', "Account blocked: too many attempts");
define('LANG_ERROR_NEWPASSWORD_REQUIRED',"Your new password must differ from the previous one");
define('LANG_ERROR_NEWPASS_EMPTY',"You must provide a new password");
define('LANG_ERROR_PASSWORD_INCORRECT',"The current password you supplied is incorrect");
define('LANG_ERROR_FACEBOOK_DUPLICATE_ID',"We already have an account linked to your Facebook account");
define('LANG_ERROR_EXTREG_DISABLED_HEADER',"Account creation disabled");
define('LANG_ERROR_EXTREG_DISABLED_MESSAGE',"It is not allowed to create an account. Please contact the administrator");



#OTHER MESSAGES
define('LANG_USER_ADDED_TITLE',"User has been created");
define('LANG_USER_ADDED',"The user has been succesfully added");
define('LANG_USER_EXT_ADDED_TITLE',"Account created");
define('LANG_USER_EXT_ADDED',"Your account has been created.");
define('LANG_USER_EXT_ADMIN_CHECK','One of our administrators will now check your account an approve it.');
define('LANG_USER_EXT_USER_CHECK','You have received an e-mail with some information about how to activate your account.');
define('LANG_USER_EXT_CONTINUE','Continue');
define('LANG_USER_EDITED_TITLE',"User has been edited");
define('LANG_USER_EDITED',"The changes to this user have been successfully made");
define('LANG_USERGROUP_ADDED_TITLE',"Usergroup Added");
define('LANG_USERGROUP_ADDED',"The usergroup has been added, you can nog start adding users to it.");
define('LANG_USERGROUP_EDITED_TITLE',"Usergroup has been changed");
define('LANG_USERGROUP_EDITED',"Your changes have been saved.");
define('LANG_PASSWORD_FORCEDCHANGE',"The administrator has stated that you need to change your password when you first login on this site. Please choose a new password.");
define('LANG_PASSWORD_CHANGED_HEADER',"Password changed");
define('LANG_PASSWORD_CHANGED',"Your password has been changed. Next time you login you will have to use the new password");

#USERACTIVATION
define('LANG_USER_SELF_ACTIVATION_HEADER',"Confirm your account");
define('LANG_USER_SELF_ACTIVATION_TEXT',"You have succesfully created an account on <!CC VAR [sitename]>. In an attempt to stop spammers from joining the site we have implemented a user activation system. Please click the link below to confirm your account<p><h3><a href='<!CC VAR [activationlink]>'>Activate your account</a></h3>");
define('LANG_USER_SELF_ACTIVATION_INFORMATION',"In an effort to avoid spammers on <!CC VAR [sitename]> we gave implemented an activation system into this site. This will make it difficult for bots to register. Please follow complete the form below to activate your account.");
define('LANG_USER_SELF_ACTIVATION_STEP1_HEADER',"Step 1 - Type in your username and password");
define('LANG_USER_SELF_ACTIVATION_STEP2_HEADER',"Stap 2 - anti-spam control");
define('LANG_USER_SELF_ACTIVATION_ENTERCAPTCHA',"Type the words above");
define('LANG_USER_SELF_ACTIVATION_CANTREAD',"I can't read it, give me different words");
define('LANG_USER_SELF_ACTIVATION_ACTIVATE',"Activate my account");
define('LANG_USER_SELF_ACTIVATION_WELCOMETO',"Welcome to ");
define('LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_TITLE',"Almost there...");
define('LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_BODY',"You have successfully verified your account. However, to be sure that you are not a spammer one of our admins will now manually check your data and activate your account. You will receive an E-mail as soon as your account can be used. Please note that even though we try to activate all new accounts within 24 hours it can take a bit longer."); 
define('LANG_USER_SELF_ACTIVATION_ADMIN_NEEDED_LINK',"Go back");
define('LANG_USER_SELF_ACTIVATION_DONE_TITLE',"Account activated");
define('LANG_USER_SELF_ACTIVATION_DONE_BODY',"You have successfully activated your account. You can now login using your username and password");

#COUNTRY LIST
define('LANG_COUNTRY_AF',"Afganistan");
define('LANG_COUNTRY_AX',"Aland Islands");
define('LANG_COUNTRY_AL',"Albania");
define('LANG_COUNTRY_DZ',"Algeria");
define('LANG_COUNTRY_AS',"American Samoa");
define('LANG_COUNTRY_AD',"Andorra");
define('LANG_COUNTRY_AO',"Angola");
define('LANG_COUNTRY_AI',"Anguilla");
define('LANG_COUNTRY_AQ',"Antartica");
define('LANG_COUNTRY_AG',"Antigua and Barbuda");
define('LANG_COUNTRY_AR',"Argentina");
define('LANG_COUNTRY_AM',"Armenia");
define('LANG_COUNTRY_AW',"Aruba");
define('LANG_COUNTRY_AU',"Australia");
define('LANG_COUNTRY_AT',"Austria");
define('LANG_COUNTRY_AZ',"Azerbaijan");
define('LANG_COUNTRY_BS',"Bahamas");
define('LANG_COUNTRY_BH',"Bahrain");
define('LANG_COUNTRY_BD',"Bangladesh");
define('LANG_COUNTRY_BB',"Barbados");
define('LANG_COUNTRY_BY',"Belarus");
define('LANG_COUNTRY_BE',"Belgium");
define('LANG_COUNTRY_BZ',"Belize");
define('LANG_COUNTRY_BJ',"Benin");
define('LANG_COUNTRY_BM',"Bermuda");
define('LANG_COUNTRY_BT',"Bhutan");
define('LANG_COUNTRY_BO',"Bolivia");
define('LANG_COUNTRY_BA',"Bosnia And Herzegovina");
define('LANG_COUNTRY_BW',"Botswana");
define('LANG_COUNTRY_BV',"Bouvet Island");
define('LANG_COUNTRY_BR',"Brazil");
define('LANG_COUNTRY_IO',"Britisch Indian Ocean Territory");
define('LANG_COUNTRY_BN',"Brunei Darussalam");
define('LANG_COUNTRY_BG',"Bulgaria");
define('LANG_COUNTRY_BF',"Burkina Faso");
define('LANG_COUNTRY_BI',"Burundi");
define('LANG_COUNTRY_KH',"Cambodia");
define('LANG_COUNTRY_CM',"Cameroon");
define('LANG_COUNTRY_CA',"Canada");
define('LANG_COUNTRY_CV',"Cape Verde");
define('LANG_COUNTRY_KY',"Cayman Islands");
define('LANG_COUNTRY_CF',"Central African Republic");
define('LANG_COUNTRY_TD',"Chad");
define('LANG_COUNTRY_CL',"Chile");
define('LANG_COUNTRY_CN',"China");
define('LANG_COUNTRY_CX',"Christmas Island");
define('LANG_COUNTRY_CC',"Cocos (Keeling) Islands");
define('LANG_COUNTRY_CO',"Colombia");
define('LANG_COUNTRY_KM',"Comoros");
define('LANG_COUNTRY_CG',"Congo");
define('LANG_COUNTRY_CK',"Cook Islands");
define('LANG_COUNTRY_CR',"Costa Rica");
define('LANG_COUNTRY_CI',"C�te d'Ivoire");
define('LANG_COUNTRY_HR',"Croatia");
define('LANG_COUNTRY_CU',"Cuba");
define('LANG_COUNTRY_CY',"Cyprus");
define('LANG_COUNTRY_CZ',"Czech Republic");
define('LANG_COUNTRY_DK',"Denmark");
define('LANG_COUNTRY_DJ',"Djibouti");
define('LANG_COUNTRY_DM',"Dominica");
define('LANG_COUNTRY_DO',"Dominican Republic");
define('LANG_COUNTRY_EC',"Ecuador");
define('LANG_COUNTRY_EG',"Egypt");
define('LANG_COUNTRY_SV',"Salvador");
define('LANG_COUNTRY_GQ',"Equatorial Guinea");
define('LANG_COUNTRY_ER',"Eritrea");
define('LANG_COUNTRY_EE',"Estonia");
define('LANG_COUNTRY_ET',"Ethiopia");
define('LANG_COUNTRY_FK',"Falkland Islands (Malvinas)");
define('LANG_COUNTRY_FO',"Faroe Islands");
define('LANG_COUNTRY_FJ',"Fiji");
define('LANG_COUNTRY_FI',"Finland");
define('LANG_COUNTRY_FR',"France");
define('LANG_COUNTRY_GF',"French Guiana");
define('LANG_COUNTRY_PF',"French Polynesia");
define('LANG_COUNTRY_TF',"French Southern Territories");
define('LANG_COUNTRY_GA',"Gabon");
define('LANG_COUNTRY_GM',"Gambia");
define('LANG_COUNTRY_GE',"Georgia");
define('LANG_COUNTRY_DE',"Germany");
define('LANG_COUNTRY_GH',"Ghana");
define('LANG_COUNTRY_GI',"Gibraltar");
define('LANG_COUNTRY_GR',"Greece");
define('LANG_COUNTRY_GL',"Greenland");
define('LANG_COUNTRY_GD',"Granada");
define('LANG_COUNTRY_GP',"Guadeloupe");
define('LANG_COUNTRY_GU',"Guam");
define('LANG_COUNTRY_GT',"Guatemala");
define('LANG_COUNTRY_GG',"Guernsey");
define('LANG_COUNTRY_GN',"Guinea");
define('LANG_COUNTRY_GW',"Guinea-Bissau");
define('LANG_COUNTRY_GY',"Guyana");
define('LANG_COUNTRY_HT',"Haiti");
define('LANG_COUNTRY_HM',"Heard Island and Mcdonald Islands");
define('LANG_COUNTRY_VA',"Vatican City State");
define('LANG_COUNTRY_HN',"Honduras");
define('LANG_COUNTRY_HK',"Hong Kong");
define('LANG_COUNTRY_HU',"Hungary");
define('LANG_COUNTRY_IS',"Iceland");
define('LANG_COUNTRY_IN',"India");
define('LANG_COUNTRY_ID',"Indonesia");
define('LANG_COUNTRY_IR',"Iran");
define('LANG_COUNTRY_IQ',"Iraq");
define('LANG_COUNTRY_IE',"Ireland");
define('LANG_COUNTRY_IM',"Isle of Man");
define('LANG_COUNTRY_IL',"Israel");
define('LANG_COUNTRY_IT',"Italy");
define('LANG_COUNTRY_JM',"Jamaica");
define('LANG_COUNTRY_JP',"Japan");
define('LANG_COUNTRY_JE',"Jersey");
define('LANG_COUNTRY_JO',"Jordan");
define('LANG_COUNTRY_KZ',"Kazakhstan");
define('LANG_COUNTRY_KE',"Kenya");
define('LANG_COUNTRY_KI',"Kiribati");
define('LANG_COUNTRY_KP',"Korea Democratic people's republic");
define('LANG_COUNTRY_KR',"Korea republic of");
define('LANG_COUNTRY_KW',"Kuwait");
define('LANG_COUNTRY_KG',"Kyrgyzstan");
define('LANG_COUNTRY_LA',"Lao People's Democratic Republic");
define('LANG_COUNTRY_LV',"Latvia");
define('LANG_COUNTRY_LB',"Lebanon");
define('LANG_COUNTRY_LS',"Lesotho");
define('LANG_COUNTRY_LR',"Liberia");
define('LANG_COUNTRY_LY',"Libyan Arab Jamahirya");
define('LANG_COUNTRY_LI',"Liechtenstein");
define('LANG_COUNTRY_LT',"Lithuania");
define('LANG_COUNTRY_LU',"Luxembourg");
define('LANG_COUNTRY_MO',"Macao");
define('LANG_COUNTRY_MK',"Macedonia");
define('LANG_COUNTRY_MG',"Madagascar");
define('LANG_COUNTRY_MW',"Malawi");
define('LANG_COUNTRY_MY',"Malaysia");
define('LANG_COUNTRY_MV',"Maladives");
define('LANG_COUNTRY_ML',"Mali");
define('LANG_COUNTRY_MT',"Malta");
define('LANG_COUNTRY_MH',"Marshall Islands");
define('LANG_COUNTRY_MQ',"Martinique");
define('LANG_COUNTRY_MR',"Mauritania");
define('LANG_COUNTRY_MU',"Mauritius");
define('LANG_COUNTRY_YT',"Mayotte");
define('LANG_COUNTRY_MX',"Mexico");
define('LANG_COUNTRY_FM',"Micronesia");
define('LANG_COUNTRY_MD',"Moldavia");
define('LANG_COUNTRY_MC',"Monaco");
define('LANG_COUNTRY_MN',"Mongolia");
define('LANG_COUNTRY_ME',"Montenegro");
define('LANG_COUNTRY_MS',"Montserrat");
define('LANG_COUNTRY_MA',"Marocco");
define('LANG_COUNTRY_MZ',"Mozambique");
define('LANG_COUNTRY_MM',"Myanmar");
define('LANG_COUNTRY_NA',"Namibia");
define('LANG_COUNTRY_NR',"Nauru");
define('LANG_COUNTRY_NP',"Nepal");
define('LANG_COUNTRY_NL',"Netherlands");
define('LANG_COUNTRY_AN',"Netherlands Antilles");
define('LANG_COUNTRY_NC',"New Caledonia");
define('LANG_COUNTRY_NZ',"New Zealand");
define('LANG_COUNTRY_NI',"Nicarague");
define('LANG_COUNTRY_NE',"Niger");
define('LANG_COUNTRY_NG',"Nigeria");
define('LANG_COUNTRY_NU',"Niue");
define('LANG_COUNTRY_NF',"Norforlk");
define('LANG_COUNTRY_MP',"Northern Mariana Islands");
define('LANG_COUNTRY_NO',"Norway");
define('LANG_COUNTRY_OM',"Oman");
define('LANG_COUNTRY_PK',"Pakistan");
define('LANG_COUNTRY_PW',"Palau");
define('LANG_COUNTRY_PS',"Palestinian Territory, occupied");
define('LANG_COUNTRY_PA',"Panama");
define('LANG_COUNTRY_PG',"Papua New Guinea");
define('LANG_COUNTRY_PY',"Paraguay");
define('LANG_COUNTRY_PE',"Peru");
define('LANG_COUNTRY_PH',"Philippines");
define('LANG_COUNTRY_PN',"Pitcairn");
define('LANG_COUNTRY_PL',"Poland");
define('LANG_COUNTRY_PT',"Portugal");
define('LANG_COUNTRY_PR',"Puerto Rico");
define('LANG_COUNTRY_QA',"Quatar");
define('LANG_COUNTRY_RE',"R�union");
define('LANG_COUNTRY_RO',"Romania");
define('LANG_COUNTRY_RU',"Russian Federation");
define('LANG_COUNTRY_RW',"Rwanda");
define('LANG_COUNTRY_BL',"Saint Barth�lemy");
define('LANG_COUNTRY_SH',"Saint Helena");
define('LANG_COUNTRY_KN',"Saint Kitts and Nevis");
define('LANG_COUNTRY_LC',"Lucia");
define('LANG_COUNTRY_MF',"Saint Martine");
define('LANG_COUNTRY_PM',"Saint Pierre and Miquelon");
define('LANG_COUNTRY_VC',"Saint Vincent And the Grenadines");
define('LANG_COUNTRY_WS',"Samoa");
define('LANG_COUNTRY_SM',"San Marino");
define('LANG_COUNTRY_ST',"Sao Tome And Principe");
define('LANG_COUNTRY_SA',"Saudi Arabia");
define('LANG_COUNTRY_SN',"Senegal");
define('LANG_COUNTRY_RS',"Serbia");
define('LANG_COUNTRY_SC',"Seychelles");
define('LANG_COUNTRY_SL',"Sierra Leone");
define('LANG_COUNTRY_SG',"Singapore");
define('LANG_COUNTRY_SK',"Slovakia");
define('LANG_COUNTRY_SI',"Slovenia");
define('LANG_COUNTRY_SB',"Solomon Islands");
define('LANG_COUNTRY_SO',"Somalia");
define('LANG_COUNTRY_ZA',"South Africa");
define('LANG_COUNTRY_GS',"South Georgia and the South Sandwich Islands");
define('LANG_COUNTRY_ES',"Spain");
define('LANG_COUNTRY_LK',"Sri Lanka");
define('LANG_COUNTRY_SD',"Sudan");
define('LANG_COUNTRY_SR',"Suriname");
define('LANG_COUNTRY_SJ',"Svalbard and Jan Mayen");
define('LANG_COUNTRY_SZ',"Swaziland");
define('LANG_COUNTRY_SE',"Sweden");
define('LANG_COUNTRY_CH',"Switzerland");
define('LANG_COUNTRY_SY',"Syrian Arab republic");
define('LANG_COUNTRY_TW',"Taiwan");
define('LANG_COUNTRY_TJ',"Tajikistan");
define('LANG_COUNTRY_TZ',"Tanzania");
define('LANG_COUNTRY_TH',"Thailand");
define('LANG_COUNTRY_TL',"Timor-Leste");
define('LANG_COUNTRY_TG',"Togo");
define('LANG_COUNTRY_TK',"Tokelau");
define('LANG_COUNTRY_TO',"Tonga");
define('LANG_COUNTRY_TT',"Trinidad and Tobago");
define('LANG_COUNTRY_TN',"Tunesia");
define('LANG_COUNTRY_TR',"Turkey");
define('LANG_COUNTRY_TM',"Turkmenistan");
define('LANG_COUNTRY_TC',"Turks and Caicos Islands");
define('LANG_COUNTRY_TV',"Tuvalu");
define('LANG_COUNTRY_UG',"Uganda");
define('LANG_COUNTRY_UA',"Ukraine");
define('LANG_COUNTRY_AE',"United Arab Emirates");
define('LANG_COUNTRY_GB',"United Kingdom");
define('LANG_COUNTRY_US',"United States");
define('LANG_COUNTRY_UM',"United States Minor Outlying Islands");
define('LANG_COUNTRY_UY',"Uruguay");
define('LANG_COUNTRY_UZ',"Uzbekistan");
define('LANG_COUNTRY_VU',"Vanuatu");
define('LANG_COUNTRY_VE',"Venezuela");
define('LANG_COUNTRY_VN',"Vietnam");
define('LANG_COUNTRY_VG',"Virgin Islands, British");
define('LANG_COUNTRY_VI',"Virgin Islands, U.S.");
define('LANG_COUNTRY_WF',"Wallis and Futuna");
define('LANG_COUNTRY_EH',"Western Sahara");
define('LANG_COUNTRY_YE',"Yemen");
define('LANG_COUNTRY_ZM',"Zambia");
define('LANG_COUNTRY_ZW',"Zimbabwe");

?>
