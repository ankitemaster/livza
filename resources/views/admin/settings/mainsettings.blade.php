@extends('admin.layouts.sidebar')
<?php use Carbon\Carbon;?>
@section('content')
<!--********************************** Content body start ***********************************-->
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{trans('app.Home')}}</a></li>
            <li class="breadcrumb-item active"><a href="{{ URL::to('admin/mainsettings') }}">{{trans('app.General Settings')}}</a></li>
        </ol>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">&nbsp;</div>
</div>
<!-- row -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <h3 class="content-heading mt-2">{{trans('app.General Settings')}}</h3>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-validation">
                        <form class="form-valide" action="{{action('Admin\SettingsController@mainsettingsupdate')}}" method="post" enctype="multipart/form-data" onSubmit="return validatedata()">
                            @csrf
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Site Name')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="val-sitename" name="val-sitename" value="{{$details->sitename}}" placeholder="{{trans('app.Enter Site Name')}}" maxlength="30" value="{{ old('val-sitename') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9" id="sitenameerr" style="color: #fd397a">
                                            @if ($errors->has('val-sitename'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('val-sitename') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Home Page Title')}} <span class="text-danger"></span>
                                        </label>
                                        <div class="col-lg-8">
                                            <textarea name="summernoteInputtitle" rows="3" id="val-page-title" class="form-control note">{{$details->page_title}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Meta Title')}} <span class="text-danger"></span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="val-metaname" name="val-metaname" value="{{$details->meta_title}}" placeholder="{{trans('app.Enter Meta title')}}" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-9">
                                            @if ($errors->has('val-metaname'))
                                            <span class="help-block text-danger">
                                                <strong>{{ $errors->first('val-metaname') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label">{{trans('app.Free Gems on Signup')}} <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" id="val-gems" name="val-gems" value="<?php if ($details->signup_credits != '') {
                                                echo $details->signup_credits;
                                                } else {
                                                    echo "0";
                                                }?>" placeholder="{{trans('app.Enter no of gems while signup')}}" maxlength="6" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-9">
                                                @if ($errors->has('val-gems'))
                                                <span class="help-block text-danger">
                                                    <strong>{{ $errors->first('val-gems') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label">{{trans('app.Gems on Invite Friends')}} <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control" id="val-invitegems" name="val-invitegems" value="<?php if ($details->invite_credits != '') {
                                                    echo $details->invite_credits;
                                                    } else {
                                                        echo "0";
                                                    }?>" placeholder="{{trans('app.Enter no of gems while invite friends')}}" maxlength="6" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-3"></div>
                                                <div class="col-lg-9">
                                                    @if ($errors->has('val-invitegems'))
                                                    <span class="help-block text-danger">
                                                        <strong>{{ $errors->first('val-invitegems') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-lg-3 col-form-label">{{trans('app.Gems on watching Ads')}} <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-lg-8">
                                                    <input type="text" class="form-control" id="val-adsgems" name="val-adsgems" value="<?php if ($details->invite_credits != '') {
                                                        echo $details->ads_credits;
                                                        } else {
                                                            echo "0";
                                                        }?>" placeholder="{{trans('app.Enter no of gems add after watching ads')}}" maxlength="6" oninput="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-lg-3"></div>
                                                    <div class="col-lg-9">
                                                        @if ($errors->has('val-adsgems'))
                                                        <span class="help-block text-danger">
                                                            <strong>{{ $errors->first('val-adsgems') }}</strong>
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-lg-3 col-form-label">{{trans('app.Commission (Gifts to gems in %)')}}<span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" id="val-gems_commision_per" name="val-gems_commision_per" value="<?php if ($details->gems_commision_per != '') {
                                                            echo $details->gems_commision_per;
                                                            } else {
                                                                echo "0";
                                                            }?>" placeholder="{{trans('app.Commission (Gifts to gems in %)')}}" maxlength="2" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-lg-3"></div>
                                                        <div class="col-lg-9">
                                                            @if ($errors->has('val-gems_commision_per'))
                                                            <span class="help-block text-danger">
                                                                <strong>{{ $errors->first('val-gems_commision_per') }}</strong>
                                                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-lg-3 col-form-label">{{trans('app.Video Calls')}}<span class="text-danger">*</span>
                                                        </label>
                                                        <div class="col-lg-8">
                                                            <input type="text" class="form-control" id="val-calls_debits" name="val-calls_debits" value="<?php if ($details->calls_debits != '') {
                                                                echo $details->calls_debits;
                                                                } else {
                                                                    echo "0";
                                                                }?>" placeholder="{{trans('app.Video Calls')}}" maxlength="6" oninput="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-lg-3"></div>
                                                            <div class="col-lg-9">
                                                                @if ($errors->has('val-calls_debits'))
                                                                <span class="help-block text-danger">
                                                                    <strong>{{ $errors->first('val-calls_debits') }}</strong>
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-lg-3 col-form-label">{{trans('app.Schedule Video ads (in mins)')}}<span class="text-danger">*</span>
                                                            </label>
                                                            <div class="col-lg-8">
                                                                <input type="text" class="form-control" id="val-show-videoads" name="val-show-videoads" value="<?php if ($details->schedule_video_ads != '') {
                                                                    echo $details->schedule_video_ads;
                                                                    } else {
                                                                        echo "0";
                                                                    }?>" placeholder="{{trans('app.Schedule Video ads (in mins)')}}" maxlength="6" oninput="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-3"></div>
                                                                <div class="col-lg-9">
                                                                    @if ($errors->has('val-show-videoads'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('val-show-videoads') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <fieldset class="form-group">
                                                                <div class="row">
                                                                    <label class="col-form-label col-sm-3 pt-0">{{trans('app.Watch Video & Earn')}}</label>
                                                                    <div class="col-sm-8">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="radio" name="gridRadios" value="option1" checked="">
                                                                            <input type="radio" class="form-check-input" id="val-videoads" name="val-videoads" value="1" <?php if ($details->video_ads == '1') {
                                                                                echo 'checked=checked';
                                                                            }?>>
                                                                            <label class="form-check-label">
                                                                                {{trans('app.Enable')}}
                                                                            </label>
                                                                        </div>
                                                                        <div class="form-check">
                                                                            <input type="radio" class="form-check-input" id="val-videoads" name="val-videoads" value="0" <?php if ($details->video_ads == '0') {
                                                                                echo 'checked=checked';
                                                                            }?>>
                                                                            <label class="form-check-label">
                                                                                {{trans('app.Disable')}}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </fieldset>
                                                            <div class="form-group row">
                                                                <div class="col-lg-3"></div>
                                                                <div class="col-lg-9">
                                                                    @if ($errors->has('val-red-sub'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('val-red-sub') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                    @if ($errors->has('val-red-unsub'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('val-red-unsub') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-lg-3 col-form-label">{{trans('app.Push Notification key')}} <span class="text-danger">*</span>
                                                                </label>
                                                                <div class="col-lg-8">
                                                                    <textarea name="summernoteInput" rows="3" id="val-key" class="form-control note">{{$details->notification_key}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-3"></div>
                                                                <div class="col-lg-9">
                                                                    @if ($errors->has('val-key'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('val-key') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-lg-3 col-form-label">{{trans('app.Welcome Message')}}
                                                                </label>
                                                                <div class="col-lg-8">
                                                                    <textarea name="welcome_message" id="val-welcome-text" rows="5" class="form-control note">{{$details->welcome_message}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-lg-3 col-form-label">{{trans('app.Google analytics')}} <span class="text-danger">*</span>
                                                                </label>
                                                                <div class="col-lg-8">
                                                                    <textarea name="summernoteInputgoogle" id="val-analytics" rows="6" class="form-control note">{{$details->google_analytics}}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-3"></div>
                                                                <div class="col-lg-9">
                                                                    @if ($errors->has('val-analytics'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('val-analytics') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-lg-3 col-form-label">{{trans('app.Contact Mail Id')}}
                                                                </label>
                                                                <div class="col-lg-8">
                                                                    <input type="text" class="form-control" id="val-contact_emailid" name="val-contact_emailid" value="{{$details->contact_emailid}}" placeholder="{{trans('app.Enter Contact mail id')}}" maxlength="50">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-3"></div>
                                                                <div class="col-lg-9">
                                                                    @if ($errors->has('val-contact_emailid'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('val-contact_emailid') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-lg-3 col-form-label">{{trans('app.Copyrights Text')}}
                                                                </label>
                                                                <div class="col-lg-8">
                                                                    <input type="text" class="form-control" id="val-copyrights" name="val-copyrights" value="{{$details->copyrights}}" placeholder="{{trans('app.Enter Copyrights Text')}}" maxlength="100">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-lg-3"></div>
                                                                <div class="col-lg-9">
                                                                    @if ($errors->has('val-copyrights'))
                                                                    <span class="help-block text-danger">
                                                                        <strong>{{ $errors->first('val-copyrights') }}</strong>
                                                                    </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-lg-3 col-form-label">{{trans('app.Locations')}}
                                                                </label>
                                                                <div class="col-lg-8">
                                                                    <select id="multiselect" name="val-location[]" multiple="multiple" >
                                                                        <option value="Afghanistan">Afghanistan</option>
                                                                        <option value="Albania">Albania</option>
                                                                        <option value="Algeria">Algeria</option>
                                                                        <option value="American Samoa">American Samoa</option>
                                                                        <option value="Andorra">Andorra</option>
                                                                        <option value="Angola">Angola</option>
                                                                        <option value="Anguilla">Anguilla</option>
                                                                        <option value="Antartica">Antarctica</option>
                                                                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                                        <option value="Argentina">Argentina</option>
                                                                        <option value="Armenia">Armenia</option>
                                                                        <option value="Aruba">Aruba</option>
                                                                        <option value="Australia">Australia</option>
                                                                        <option value="Austria">Austria</option>
                                                                        <option value="Azerbaijan">Azerbaijan</option>
                                                                        <option value="Bahamas">Bahamas</option>
                                                                        <option value="Bahrain">Bahrain</option>
                                                                        <option value="Bangladesh">Bangladesh</option>
                                                                        <option value="Barbados">Barbados</option>
                                                                        <option value="Belarus">Belarus</option>
                                                                        <option value="Belgium">Belgium</option>
                                                                        <option value="Belize">Belize</option>
                                                                        <option value="Benin">Benin</option>
                                                                        <option value="Bermuda">Bermuda</option>
                                                                        <option value="Bhutan">Bhutan</option>
                                                                        <option value="Bolivia">Bolivia</option>
                                                                        <option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
                                                                        <option value="Botswana">Botswana</option>
                                                                        <option value="Bouvet Island">Bouvet Island</option>
                                                                        <option value="Brazil">Brazil</option>
                                                                        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                                                        <option value="Brunei Darussalam">Brunei Darussalam</option>
                                                                        <option value="Bulgaria">Bulgaria</option>
                                                                        <option value="Burkina Faso">Burkina Faso</option>
                                                                        <option value="Burundi">Burundi</option>
                                                                        <option value="Cambodia">Cambodia</option>
                                                                        <option value="Cameroon">Cameroon</option>
                                                                        <option value="Canada">Canada</option>
                                                                        <option value="Cape Verde">Cape Verde</option>
                                                                        <option value="Cayman Islands">Cayman Islands</option>
                                                                        <option value="Central African Republic">Central African Republic</option>
                                                                        <option value="Chad">Chad</option>
                                                                        <option value="Chile">Chile</option>
                                                                        <option value="China">China</option>
                                                                        <option value="Christmas Island">Christmas Island</option>
                                                                        <option value="Cocos Islands">Cocos (Keeling) Islands</option>
                                                                        <option value="Colombia">Colombia</option>
                                                                        <option value="Comoros">Comoros</option>
                                                                        <option value="Congo">Congo</option>
                                                                        <option value="Congo">Congo, the Democratic Republic of the</option>
                                                                        <option value="Cook Islands">Cook Islands</option>
                                                                        <option value="Costa Rica">Costa Rica</option>
                                                                        <option value="Cota D'Ivoire">Cote d'Ivoire</option>
                                                                        <option value="Croatia">Croatia (Hrvatska)</option>
                                                                        <option value="Cuba">Cuba</option>
                                                                        <option value="Cyprus">Cyprus</option>
                                                                        <option value="Czech Republic">Czech Republic</option>
                                                                        <option value="Denmark">Denmark</option>
                                                                        <option value="Djibouti">Djibouti</option>
                                                                        <option value="Dominica">Dominica</option>
                                                                        <option value="Dominican Republic">Dominican Republic</option>
                                                                        <option value="East Timor">East Timor</option>
                                                                        <option value="Ecuador">Ecuador</option>
                                                                        <option value="Egypt">Egypt</option>
                                                                        <option value="El Salvador">El Salvador</option>
                                                                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                                        <option value="Eritrea">Eritrea</option>
                                                                        <option value="Estonia">Estonia</option>
                                                                        <option value="Ethiopia">Ethiopia</option>
                                                                        <option value="Falkland Islands">Falkland Islands (Malvinas)</option>
                                                                        <option value="Faroe Islands">Faroe Islands</option>
                                                                        <option value="Fiji">Fiji</option>
                                                                        <option value="Finland">Finland</option>
                                                                        <option value="France">France</option>
                                                                        <option value="France Metropolitan">France, Metropolitan</option>
                                                                        <option value="French Guiana">French Guiana</option>
                                                                        <option value="French Polynesia">French Polynesia</option>
                                                                        <option value="French Southern Territories">French Southern Territories</option>
                                                                        <option value="Gabon">Gabon</option>
                                                                        <option value="Gambia">Gambia</option>
                                                                        <option value="Georgia">Georgia</option>
                                                                        <option value="Germany">Germany</option>
                                                                        <option value="Ghana">Ghana</option>
                                                                        <option value="Gibraltar">Gibraltar</option>
                                                                        <option value="Greece">Greece</option>
                                                                        <option value="Greenland">Greenland</option>
                                                                        <option value="Grenada">Grenada</option>
                                                                        <option value="Guadeloupe">Guadeloupe</option>
                                                                        <option value="Guam">Guam</option>
                                                                        <option value="Guatemala">Guatemala</option>
                                                                        <option value="Guinea">Guinea</option>
                                                                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                                        <option value="Guyana">Guyana</option>
                                                                        <option value="Haiti">Haiti</option>
                                                                        <option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
                                                                        <option value="Holy See">Holy See (Vatican City State)</option>
                                                                        <option value="Honduras">Honduras</option>
                                                                        <option value="Hong Kong">Hong Kong</option>
                                                                        <option value="Hungary">Hungary</option>
                                                                        <option value="Iceland">Iceland</option>
                                                                        <option value="India">India</option>
                                                                        <option value="Indonesia">Indonesia</option>
                                                                        <option value="Iran">Iran (Islamic Republic of)</option>
                                                                        <option value="Iraq">Iraq</option>
                                                                        <option value="Ireland">Ireland</option>
                                                                        <option value="Israel">Israel</option>
                                                                        <option value="Italy">Italy</option>
                                                                        <option value="Jamaica">Jamaica</option>
                                                                        <option value="Japan">Japan</option>
                                                                        <option value="Jordan">Jordan</option>
                                                                        <option value="Kazakhstan">Kazakhstan</option>
                                                                        <option value="Kenya">Kenya</option>
                                                                        <option value="Kiribati">Kiribati</option>
                                                                        <option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
                                                                        <option value="Korea">Korea, Republic of</option>
                                                                        <option value="Kuwait">Kuwait</option>
                                                                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                        <option value="Lao">Lao People's Democratic Republic</option>
                                                                        <option value="Latvia">Latvia</option>
                                                                        <option value="Lebanon">Lebanon</option>
                                                                        <option value="Lesotho">Lesotho</option>
                                                                        <option value="Liberia">Liberia</option>
                                                                        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                                                        <option value="Liechtenstein">Liechtenstein</option>
                                                                        <option value="Lithuania">Lithuania</option>
                                                                        <option value="Luxembourg">Luxembourg</option>
                                                                        <option value="Macau">Macau</option>
                                                                        <option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
                                                                        <option value="Madagascar">Madagascar</option>
                                                                        <option value="Malawi">Malawi</option>
                                                                        <option value="Malaysia">Malaysia</option>
                                                                        <option value="Maldives">Maldives</option>
                                                                        <option value="Mali">Mali</option>
                                                                        <option value="Malta">Malta</option>
                                                                        <option value="Marshall Islands">Marshall Islands</option>
                                                                        <option value="Martinique">Martinique</option>
                                                                        <option value="Mauritania">Mauritania</option>
                                                                        <option value="Mauritius">Mauritius</option>
                                                                        <option value="Mayotte">Mayotte</option>
                                                                        <option value="Mexico">Mexico</option>
                                                                        <option value="Micronesia">Micronesia, Federated States of</option>
                                                                        <option value="Moldova">Moldova, Republic of</option>
                                                                        <option value="Monaco">Monaco</option>
                                                                        <option value="Mongolia">Mongolia</option>
                                                                        <option value="Montserrat">Montserrat</option>
                                                                        <option value="Morocco">Morocco</option>
                                                                        <option value="Mozambique">Mozambique</option>
                                                                        <option value="Myanmar">Myanmar</option>
                                                                        <option value="Namibia">Namibia</option>
                                                                        <option value="Nauru">Nauru</option>
                                                                        <option value="Nepal">Nepal</option>
                                                                        <option value="Netherlands">Netherlands</option>
                                                                        <option value="Netherlands Antilles">Netherlands Antilles</option>
                                                                        <option value="New Caledonia">New Caledonia</option>
                                                                        <option value="New Zealand">New Zealand</option>
                                                                        <option value="Nicaragua">Nicaragua</option>
                                                                        <option value="Niger">Niger</option>
                                                                        <option value="Nigeria">Nigeria</option>
                                                                        <option value="Niue">Niue</option>
                                                                        <option value="Norfolk Island">Norfolk Island</option>
                                                                        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                                        <option value="Norway">Norway</option>
                                                                        <option value="Oman">Oman</option>
                                                                        <option value="Pakistan">Pakistan</option>
                                                                        <option value="Palau">Palau</option>
                                                                        <option value="Panama">Panama</option>
                                                                        <option value="Papua New Guinea">Papua New Guinea</option>
                                                                        <option value="Paraguay">Paraguay</option>
                                                                        <option value="Peru">Peru</option>
                                                                        <option value="Philippines">Philippines</option>
                                                                        <option value="Pitcairn">Pitcairn</option>
                                                                        <option value="Poland">Poland</option>
                                                                        <option value="Portugal">Portugal</option>
                                                                        <option value="Puerto Rico">Puerto Rico</option>
                                                                        <option value="Qatar">Qatar</option>
                                                                        <option value="Reunion">Reunion</option>
                                                                        <option value="Romania">Romania</option>
                                                                        <option value="Russia">Russian Federation</option>
                                                                        <option value="Rwanda">Rwanda</option>
                                                                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                                        <option value="Saint LUCIA">Saint LUCIA</option>
                                                                        <option value="Saint Vincent">Saint Vincent and the Grenadines</option>
                                                                        <option value="Samoa">Samoa</option>
                                                                        <option value="San Marino">San Marino</option>
                                                                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                                                        <option value="Senegal">Senegal</option>
                                                                        <option value="Seychelles">Seychelles</option>
                                                                        <option value="Sierra">Sierra Leone</option>
                                                                        <option value="Singapore">Singapore</option>
                                                                        <option value="Slovakia">Slovakia (Slovak Republic)</option>
                                                                        <option value="Slovenia">Slovenia</option>
                                                                        <option value="Solomon Islands">Solomon Islands</option>
                                                                        <option value="Somalia">Somalia</option>
                                                                        <option value="South Africa">South Africa</option>
                                                                        <option value="South Georgia">South Georgia and the South Sandwich Islands</option>
                                                                        <option value="Spain">Spain</option>
                                                                        <option value="SriLanka">Sri Lanka</option>
                                                                        <option value="St. Helena">St. Helena</option>
                                                                        <option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
                                                                        <option value="Sudan">Sudan</option>
                                                                        <option value="Suriname">Suriname</option>
                                                                        <option value="Svalbard">Svalbard and Jan Mayen Islands</option>
                                                                        <option value="Swaziland">Swaziland</option>
                                                                        <option value="Sweden">Sweden</option>
                                                                        <option value="Switzerland">Switzerland</option>
                                                                        <option value="Syria">Syrian Arab Republic</option>
                                                                        <option value="Taiwan">Taiwan, Province of China</option>
                                                                        <option value="Tajikistan">Tajikistan</option>
                                                                        <option value="Tanzania">Tanzania, United Republic of</option>
                                                                        <option value="Thailand">Thailand</option>
                                                                        <option value="Togo">Togo</option>
                                                                        <option value="Tokelau">Tokelau</option>
                                                                        <option value="Tonga">Tonga</option>
                                                                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                                        <option value="Tunisia">Tunisia</option>
                                                                        <option value="Turkey">Turkey</option>
                                                                        <option value="Turkmenistan">Turkmenistan</option>
                                                                        <option value="Turks and Caicos">Turks and Caicos Islands</option>
                                                                        <option value="Tuvalu">Tuvalu</option>
                                                                        <option value="Uganda">Uganda</option>
                                                                        <option value="Ukraine">Ukraine</option>
                                                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                                                        <option value="United Kingdom">United Kingdom</option>
                                                                        <option value="United States">United States</option>
                                                                        <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                                                        <option value="Uruguay">Uruguay</option>
                                                                        <option value="Uzbekistan">Uzbekistan</option>
                                                                        <option value="Vanuatu">Vanuatu</option>
                                                                        <option value="Venezuela">Venezuela</option>
                                                                        <option value="Vietnam">Viet Nam</option>
                                                                        <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                                                                        <option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
                                                                        <option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
                                                                        <option value="Western Sahara">Western Sahara</option>
                                                                        <option value="Yemen">Yemen</option>
                                                                        <option value="Yugoslavia">Yugoslavia</option>
                                                                        <option value="Zambia">Zambia</option>
                                                                        <option value="Zimbabwe">Zimbabwe</option>
                                                                    </select>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-lg-9 ml-auto">
                                                                <button type="submit" class="btn btn-primary">{{trans('app.Save')}}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#multiselect').multiselect({
                                onSelectedText: 'Select Location',
                                enableFiltering: true,
                                enableCaseInsensitiveFiltering: true,
                                buttonWidth:250,
                                maxHeight: 250  
                            });
                            let locationarray=<?php echo json_encode($app_locations );?>;
                            $("#multiselect").val(locationarray);
                            $("#multiselect").multiselect("refresh");
                        });
                    </script>
                    <style type="text/css">
                        .note {
                            margin-top: 0px;
                            margin-bottom: 0px;
                            color: #fff;
                            padding: 0.375rem 0.75rem;
                            width: 100%;
                            height: 85px;
                        }
                        .multiselect{
                            color: #fff !important;
                            background-color: #05ac90 !important;
                            border-color: #05ac90 !important;
                        }
                        .multiselect-search{
                            height: 35px !important;
                            background: none !important;
                            color: #000000 !important;
                        }
                    </style>
                    @endsection