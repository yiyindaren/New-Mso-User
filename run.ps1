[CmdletBinding()]
Param([string]$d, [string]$u, [string]$p)
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser -Force
$CommandList = (Get-Command -All)
If (-Not("Connect-MsolService" -in $CommandList.Name)) { Echo "Installing..."; Install-Module -Scope CurrentUser -Name MSOnline -Force }

<#
  Name        : SKU
  -------------------------------------
  STREAM      : STREAM
  FLOW_Free   : FLOW_FREE
  PowerApps   : POWERAPPS_VIRAL
  PowerBI_Free: POWER_BI_STANDARD
  PowerBI_Pro : POWER_BI_PRO
  Intune      : INTUNE_A
  Security E3 : EMS
  Business App：SMB_APPS
  Business Ess: O365_BUSINESS_ESSENTIALS
  F1          : DESKLESSPACK
  E1          : STANDARDPACK
  E2          : STANDARDWOFFPACK
  E3          : ENTERPRISEPACK
  E3_MSDN     : DEVELOPERPACK
  A1_Student  : STANDARDWOFFPACK_STUDENT
  A1_Faculty  : STANDARDWOFFPACK_FACULTY
  A1P_Student : STANDARDWOFFPACK_IW_STUDENT
  A1P_Faculty : STANDARDWOFFPACK_IW_FACULTY
#>

#####  Admin Info  #####

$AdminUser = "admin@test.com"
$AdminPwd = "Test1234"


$SecureString = ConvertTo-SecureString -AsPlainText "${AdminPwd}" -Force
$MySecureCreds = New-Object -TypeName System.Management.Automation.PSCredential -ArgumentList ${AdminUser},${SecureString}
Connect-MsolService -Credential $MySecureCreds 2>&1>$null
If (-Not $?) { Echo "Error: Authentication Failed"; Exit 1; }
$UserSKU_Full = (Get-MsolAccountSku)
$UserORG = ($UserSKU_Full.AccountSkuId -Split ":")[0]
If ([String]::IsNullOrEmpty($UserORG)) { Echo "Error: Unknown Organization"; Exit 1; }
If ([String]::IsNullOrEmpty($($d).Trim())) { 
  Do { $DisplayName = (Read-Host "`nSpecify User Display Name") } While ([String]::IsNullOrEmpty($($DisplayName).Trim()))
} Else {
  $DisplayName = $d
}
If ([String]::IsNullOrEmpty($($u).Trim())) { 
  Do { $User = (Read-Host "Specify User Name") } While ([String]::IsNullOrEmpty($($User).Trim())) 
} Else {
  $User = $u
}
If ([String]::IsNullOrEmpty($($p).Trim())) { 
  Do { $Passwd = (Read-Host "Specify User Password") } While ([String]::IsNullOrEmpty($($Passwd).Trim()))
} Else {
  $Passwd = $p
}
# A1 + E3
$MyLicenseOptions = New-MsolLicenseOptions -AccountSkuId "${UserORG}:ENTERPRISEPACK" -DisabledPlans YAMMER_ENTERPRISE,RMS_S_ENTERPRISE,MCOSTANDARD,SHAREPOINTWAC,SHAREPOINTENTERPRISE,EXCHANGE_S_ENTERPRISE
$AccResult = (New-MsolUser -DisplayName "${DisplayName}" -UserPrincipalName "${User}" -UsageLocation "HK" -LicenseAssignment ${UserORG}:STANDARDWOFFPACK_STUDENT,${UserORG}:ENTERPRISEPACK -LicenseOptions $MyLicenseOptions -Password "${Passwd}")
# A1
#$AccResult = (New-MsolUser -DisplayName "${DisplayName}" -UserPrincipalName "${User}" -UsageLocation "HK" -LicenseAssignment "${UserORG}:STANDARDWOFFPACK_STUDENT" -Password "${Passwd}")
# E3
#$AccResult = (New-MsolUser -DisplayName "${DisplayName}" -UserPrincipalName "${User}" -UsageLocation "HK" -LicenseAssignment "${UserORG}:ENTERPRISEPACK" -Password "${Passwd}")
If ([String]::IsNullOrEmpty($AccResult)) { ECHO "Error: Operation Failed"; Exit 1; } else { ECHO "Operation Finished"; Exit 0; }