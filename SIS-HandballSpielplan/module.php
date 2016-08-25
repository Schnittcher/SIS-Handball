<?
class SISHandballSpielplan extends IPSModule {

  public function Create(){
    //Never delete this line!
    parent::Create();
    //SIS-Handball
    $this->ConnectParent("{1F17C41E-F610-4327-A3A1-7E93B56AB6C2}");
  }
  public function ApplyChanges() {
    //Never delete this line!
    parent::ApplyChanges();
    $this->RegisterVariableString("Spielplan", "Spielplan", "~HTMLBox");
  }

  public function ReceiveData($JSONString) {
    $data = json_decode($JSONString);
    IPS_LogMessage("Spielplan", utf8_decode($JSONString));
    $message = '
    <table>
    <thead>
    <tr>
    <th class="col_0">Datum</th>
    <th class="col_1">Heim</th>
    <th class="col_8">Gast</th>
    </tr>
    </thead>
    <tbody>';
    foreach ($data->Buffer->Spielplan->Spiel as $spiel):
      if ($spiel->Gast == $data->Buffer->Mannschaft or $spiel->Heim == $data->Buffer->Mannschaft)	{
        $message .= '<tr>';
        $message .='<td class="col_0">'.date("d.m.Y H:i ",strToTime($spiel->SpielVon)).'Uhr</td>';
        $message .='<td class="col_0">'.$spiel->Heim.'</td>';
        $message .='<td class="col_0">'.$spiel->Gast.'</td>';
        $message .= '</tr>';
      }
    endforeach;
    $message .= '</tbody></table>';
    SetValue($this->GetIDForIdent("Spielplan") ,utf8_decode($message));
  }
}
