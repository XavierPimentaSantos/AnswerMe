@extends('layouts.app')

@section('content') 
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <label for="name">Name</label>
    <input id="name" type="text" name="name" maxlength="250" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="username">Username</label>
    <input id="username" type="text" name="username" maxlength="250" value="{{ old('username') }}" required>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" maxlength="250" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="nationality">Nationality</label>
    <select id="nationality" name="nationality" value="{{ old('nationality') }}">;
        <option value=''>Select Country</option>;
        <option value="AFG">AFG</option>;
        <option value="ALA">ALA</option>;
        <option value="ALB">ALB</option>;
        <option value="DZA">DZA</option>;
        <option value="ASM">ASM</option>;
        <option value="AND">AND</option>;
        <option value="AGO">AGO</option>;
        <option value="AIA">AIA</option>;
        <option value="ATA">ATA</option>;
        <option value="ATG">ATG</option>;
        <option value="ARG">ARG</option>;
        <option value="ARM">ARM</option>;
        <option value="ABW">ABW</option>;
        <option value="AUS">AUS</option>;
        <option value="AUT">AUT</option>;
        <option value="AZE">AZE</option>;
        <option value="BHS">BHS</option>;
        <option value="BHR">BHR</option>;
        <option value="BGD">BGD</option>;
        <option value="BRB">BRB</option>;
        <option value="BLR">BLR</option>;
        <option value="BEL">BEL</option>;
        <option value="BLZ">BLZ</option>;
        <option value="BEN">BEN</option>;
        <option value="BMU">BMU</option>;
        <option value="BTN">BTN</option>;
        <option value="BOL">BOL</option>;
        <option value="BES">BES</option>;
        <option value="BIH">BIH</option>;
        <option value="BWA">BWA</option>;
        <option value="BVT">BVT</option>;
        <option value="BRA">BRA</option>;
        <option value="IOT">IOT</option>;
        <option value="BRN">BRN</option>;
        <option value="BGR">BGR</option>;
        <option value="BFA">BFA</option>;
        <option value="BDI">BDI</option>;
        <option value="CPV">CPV</option>;
        <option value="KHM">KHM</option>;
        <option value="CMR">CMR</option>;
        <option value="CAN">CAN</option>;
        <option value="CYM">CYM</option>;
        <option value="CAF">CAF</option>;
        <option value="TCD">TCD</option>;
        <option value="CHL">CHL</option>;
        <option value="CHN">CHN</option>;
        <option value="CXR">CXR</option>;
        <option value="CCK">CCK</option>;
        <option value="COL">COL</option>;
        <option value="COM">COM</option>;
        <option value="COG">COG</option>;
        <option value="COD">COD</option>;
        <option value="COK">COK</option>;
        <option value="CRI">CRI</option>;
        <option value="HRV">HRV</option>;
        <option value="CUB">CUB</option>;
        <option value="CUW">CUW</option>;
        <option value="CYP">CYP</option>;
        <option value="CZE">CZE</option>;
        <option value="CIV">CIV</option>;
        <option value="DNK">DNK</option>;
        <option value="DJI">DJI</option>;
        <option value="DMA">DMA</option>;
        <option value="DOM">DOM</option>;
        <option value="ECU">ECU</option>;
        <option value="EGY">EGY</option>;
        <option value="SLV">SLV</option>;
        <option value="GNQ">GNQ</option>;
        <option value="ERI">ERI</option>;
        <option value="EST">EST</option>;
        <option value="ETH">ETH</option>;
        <option value="FLK">FLK</option>;
        <option value="FRO">FRO</option>;
        <option value="FJI">FJI</option>;
        <option value="FIN">FIN</option>;
        <option value="FRA">FRA</option>;
        <option value="GUF">GUF</option>;
        <option value="PYF">PYF</option>;
        <option value="ATF">ATF</option>;
        <option value="GAB">GAB</option>;
        <option value="GMB">GMB</option>;
        <option value="GEO">GEO</option>;
        <option value="DEU">DEU</option>;
        <option value="GHA">GHA</option>;
        <option value="GIB">GIB</option>;
        <option value="GRC">GRC</option>;
        <option value="GRL">GRL</option>;
        <option value="GRD">GRD</option>;
        <option value="GLP">GLP</option>;
        <option value="GUM">GUM</option>;
        <option value="GTM">GTM</option>;
        <option value="GGY">GGY</option>;
        <option value="GIN">GIN</option>;
        <option value="GNB">GNB</option>;
        <option value="GUY">GUY</option>;
        <option value="HTI">HTI</option>;
        <option value="HMD">HMD</option>;
        <option value="VAT">VAT</option>;
        <option value="HND">HND</option>;
        <option value="HKG">HKG</option>;
        <option value="HUN">HUN</option>;
        <option value="ISL">ISL</option>;
        <option value="IND">IND</option>;
        <option value="IDN">IDN</option>;
        <option value="IRN">IRN</option>;
        <option value="IRQ">IRQ</option>;
        <option value="IRL">IRL</option>;
        <option value="IMN">IMN</option>;
        <option value="ISR">ISR</option>;
        <option value="ITA">ITA</option>;
        <option value="JAM">JAM</option>;
        <option value="JPN">JPN</option>;
        <option value="JEY">JEY</option>;
        <option value="JOR">JOR</option>;
        <option value="KAZ">KAZ</option>;
        <option value="KEN">KEN</option>;
        <option value="KIR">KIR</option>;
        <option value="PRK">PRK</option>;
        <option value="KOR">KOR</option>;
        <option value="KWT">KWT</option>;
        <option value="KGZ">KGZ</option>;
        <option value="LAO">LAO</option>;
        <option value="LVA">LVA</option>;
        <option value="LBN">LBN</option>;
        <option value="LSO">LSO</option>;
        <option value="LBR">LBR</option>;
        <option value="LBY">LBY</option>;
        <option value="LIE">LIE</option>;
        <option value="LTU">LTU</option>;
        <option value="LUX">LUX</option>;
        <option value="MAC">MAC</option>;
        <option value="MDG">MDG</option>;
        <option value="MWI">MWI</option>;
        <option value="MYS">MYS</option>;
        <option value="MDV">MDV</option>;
        <option value="MLI">MLI</option>;
        <option value="MLT">MLT</option>;
        <option value="MHL">MHL</option>;
        <option value="MTQ">MTQ</option>;
        <option value="MRT">MRT</option>;
        <option value="MUS">MUS</option>;
        <option value="MYT">MYT</option>;
        <option value="MEX">MEX</option>;
        <option value="FSM">FSM</option>;
        <option value="MDA">MDA</option>;
        <option value="MCO">MCO</option>;
        <option value="MNG">MNG</option>;
        <option value="MNE">MNE</option>;
        <option value="MSR">MSR</option>;
        <option value="MAR">MAR</option>;
        <option value="MOZ">MOZ</option>;
        <option value="MMR">MMR</option>;
        <option value="NAM">NAM</option>;
        <option value="NRU">NRU</option>;
        <option value="NPL">NPL</option>;
        <option value="NLD">NLD</option>;
        <option value="NCL">NCL</option>;
        <option value="NZL">NZL</option>;
        <option value="NIC">NIC</option>;
        <option value="NER">NER</option>;
        <option value="NGA">NGA</option>;
        <option value="NIU">NIU</option>;
        <option value="NFK">NFK</option>;
        <option value="MNP">MNP</option>;
        <option value="NOR">NOR</option>;
        <option value="OMN">OMN</option>;
        <option value="PAK">PAK</option>;
        <option value="PLW">PLW</option>;
        <option value="PSE">PSE</option>;
        <option value="PAN">PAN</option>;
        <option value="PNG">PNG</option>;
        <option value="PRY">PRY</option>;
        <option value="PER">PER</option>;
        <option value="PHL">PHL</option>;
        <option value="PCN">PCN</option>;
        <option value="POL">POL</option>;
        <option value="PRT">PRT</option>;
        <option value="PRI">PRI</option>;
        <option value="QAT">QAT</option>;
        <option value="MKD">MKD</option>;
        <option value="ROU">ROU</option>;
        <option value="RUS">RUS</option>;
        <option value="RWA">RWA</option>;
        <option value="REU">REU</option>;
        <option value="BLM">BLM</option>;
        <option value="SHN">SHN</option>;
        <option value="KNA">KNA</option>;
        <option value="LCA">LCA</option>;
        <option value="MAF">MAF</option>;
        <option value="SPM">SPM</option>;
        <option value="VCT">VCT</option>;
        <option value="WSM">WSM</option>;
        <option value="SMR">SMR</option>;
        <option value="STP">STP</option>;
        <option value="SAU">SAU</option>;
        <option value="SEN">SEN</option>;
        <option value="SRB">SRB</option>;
        <option value="SYC">SYC</option>;
        <option value="SLE">SLE</option>;
        <option value="SGP">SGP</option>;
        <option value="SXM">SXM</option>;
        <option value="SVK">SVK</option>;
        <option value="SVN">SVN</option>;
        <option value="SLB">SLB</option>;
        <option value="SOM">SOM</option>;
        <option value="ZAF">ZAF</option>;
        <option value="SGS">SGS</option>;
        <option value="SSD">SSD</option>;
        <option value="ESP">ESP</option>;
        <option value="LKA">LKA</option>;
        <option value="SDN">SDN</option>;
        <option value="SUR">SUR</option>;
        <option value="SJM">SJM</option>;
        <option value="SWE">SWE</option>;
        <option value="CHE">CHE</option>;
        <option value="SYR">SYR</option>;
        <option value="TWN">TWN</option>;
        <option value="TJK">TJK</option>;
        <option value="TZA">TZA</option>;
        <option value="THA">THA</option>;
        <option value="TLS">TLS</option>;
        <option value="TGO">TGO</option>;
        <option value="TKL">TKL</option>;
        <option value="TON">TON</option>;
        <option value="TTO">TTO</option>;
        <option value="TUN">TUN</option>;
        <option value="TUR">TUR</option>;
        <option value="TKM">TKM</option>;
        <option value="TCA">TCA</option>;
        <option value="TUV">TUV</option>;
        <option value="UGA">UGA</option>;
        <option value="UKR">UKR</option>;
        <option value="ARE">ARE</option>;
        <option value="GBR">GBR</option>;
        <option value="USA">USA</option>;
        <option value="URY">URY</option>;
        <option value="UZB">UZB</option>;
        <option value="VUT">VUT</option>;
        <option value="VEN">VEN</option>;
        <option value="VNM">VNM</option>;
        <option value="VGB">VGB</option>;
        <option value="VIR">VIR</option>;
        <option value="WLF">WLF</option>;
        <option value="ESH">ESH</option>;
        <option value="YEM">YEM</option>;
        <option value="ZMB">ZMB</option>;
        <option value="ZWE">ZWE</option>;
    </select>

    <label for="bio">Bio</label>
    <textarea name="bio" id="bio" cols="20" rows="6" maxlength="300" value="{{ old('bio') }}"></textarea>
    @if ($errors->has('bio'))
      <span class="error">
          {{ $errors->first('bio') }}
      </span>
    @endif

    <label for="birthdate">Date of Birth</label>
    <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" required>
    @if ($errors->has('birthdate'))
      <span class="error">
        {{ $errors->first('brithdate') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    @if(Auth::user() && Auth::user()->isAdmin())
        <input type="hidden" name="admin_create" value="1">
    @endif

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection