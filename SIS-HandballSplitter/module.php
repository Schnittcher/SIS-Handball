<?
class SISHandballSplitter extends IPSModule {

	public function Create(){
		//Never delete this line!
		parent::Create();
		//SIS-Handball
		$this->RegisterPropertyString("user", "");
		$this->RegisterPropertyString("password", "");
		$this->RegisterPropertyString("liga", "");
		$this->RegisterPropertyString("Mannschaft","");
		$this->RegisterTimer("UpdateTabelle", 5000, 'sisSplitter_getTabelle($_IPS[\'TARGET\']);');
		$this->RegisterTimer("UpdateSpielplan", 5000, 'sisSplitter_getSpielplan($_IPS[\'TARGET\']);');


	}
	public function ApplyChanges() {
		//Never delete this line!
		parent::ApplyChanges();
	}
	public function GetConfigurationForm() {
		$mannschaftForm ='{ "type": "Select", "name": "Mannschaft", "caption": "Mannschaft",
			"options": [';

			$xml = $this->loadHandballXML(4);
			$i = 0;
			$numItems = count($xml->Platzierung);
			foreach ($xml->Platzierung as $platz) {
				$i++;
				if($i === $numItems) {
					$mannschaftForm.= '{ "label": "'.$platz->Name.'", "value": "'.$platz->Name.'" }';
				}
				else {
					$mannschaftForm.= '{ "label": "'.$platz->Name.'", "value": "'.$platz->Name.'" },';
				}
			}
			$mannschaftForm.= ']}';
			$form = '
			{
				"elements":
				[
					{ "type": "Label", "label": "Daten von SIS-Handball ermitteln"},
					{ "name": "user", "type": "ValidationTextBox", "caption": "Username" },
					{ "name": "password",     "type": "ValidationTextBox",     "caption": "Passwort" },
					{ "name": "liga",     "type": "ValidationTextBox",     "caption": "Liganummer" },
					'.$mannschaftForm.'
				]
			}';
			return $form;
		}







		public function ReceiveData($JSONString)
		{
			$data = json_decode($JSONString);
			//IPS_LogMessage("Heimmannschaft", utf8_decode($this->ReadPropertyBoolean("Mannschaft")));
			//We would parse our payload here before sending it further...
			//Lets just forward to our children
			$this->SendDataToChildren(json_encode(Array("DataID" => "{A5228F95-286E-4A3C-829F-042F6AFD00F8}", "Buffer" => $data)));
		}

		private function loadHandballXML($art) {
			$user = $this->ReadPropertyString("user");
			$password =   $this->ReadPropertyString("password");
			$liga =   $this->ReadPropertyString("liga");
			$xml = simplexml_load_file("http://www.sis-handball.de/xmlexport/xml_dyn.aspx?user=".$user."&pass=".$password."&art=".$art."&auf=".$liga);
			return $xml;
		}

		public function getTabelle() {
			$JSONString = json_encode(Array("Tabelle" =>$this->loadHandballXML(4), "Mannschaft" => $this->ReadPropertyBoolean("Mannschaft")));
			$this->ReceiveData($JSONString);
		}

		public function getSpielplan() {
			$JSONString = json_encode(Array("Spielplan" =>$this->loadHandballXML(1), "Mannschaft" => $this->ReadPropertyBoolean("Mannschaft")));
			$data = json_decode($JSONString);
			$this->SendDataToChildren(json_encode(Array("DataID" => "{F82A874F-DF1F-4902-8292-0F2B7194C9A6}", "Buffer" => $data)));
		}


	}
	?>
