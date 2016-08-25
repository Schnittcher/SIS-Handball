<?
class SISHandballTabelle extends IPSModule {

  public function Create(){
    //Never delete this line!
    parent::Create();
    //SIS-Handball
  }
  public function ApplyChanges() {
    //Never delete this line!
    parent::ApplyChanges();
    $this->ConnectParent("{1F17C41E-F610-4327-A3A1-7E93B56AB6C2}");
    $this->RegisterVariableString("Tabelle", "Tabelle", "~HTMLBox");
  }

  public function ReceiveData($JSONString) {
    IPS_LogMessage("Tabelle", utf8_decode($JSONString));

    $message = '
    <table>
    <thead>
    <tr>
    <th class="col_0">Platz</th>
    <th class="col_1">Verein</th>
    <th class="col_8">Punkte</th>
    </tr>
    </thead>
    <tbody>';
    // Empfangene Daten vom Gateway/Splitter
    $data = json_decode($JSONString);

    foreach ($data->Buffer->Tabelle->Platzierung as $platz) {
      if ($platz->Name == $data->Buffer->Mannschaft) {
        $message .= '<tr bgcolor=#088A08>';
      }
      else {
        $message .= '<tr>';
      }
      $message .='<td class="col_0">'.$platz->Nr.'</td>';
      $message .= '<td class="col_1">'.$platz->Name.'</td>';
      $message .= '<td class="col_8">0:0</td></tr>';
    }
    $message .= '</tbody></table>';
    SetValue($this->GetIDForIdent("Tabelle") ,utf8_decode($message));

    //IPS_LogMessage("Data", utf8_decode($data->Buffer->Spielklasse->Liga));

    // Datenverarbeitung und schreiben der Werte in die Statusvariablen
    //SetValue($this->GetIDForIdent("Value"), $data->Spiel->);

  }

}
?>
