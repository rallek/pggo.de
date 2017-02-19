# Wir sagen hier dem Skript, wo es den Standalone Generator hinspeichern soll
$pfad="C:\Users\Ralf\MostGenerator\"

# Nun wechseln wir in genau diesen Pfad, um dorthin den Generator zu speichern
cd $pfad
write-host "Lade jetzt den aktuellen Generator herunter. Habe ein wenig Geduld. Abhängig von der Bandbreite dauert es ein wenig, bis der Generator heruntergeladen wurde."

# Hier werden wir kurz erinnert, wohin wir den Generator speichern
write-host $pfad

# Nun laden wir den Generator tatsächlich herunter
invoke-webrequest http://modulestudio.de/downloads/ModuleStudio-generator.jar -OutFile ModuleStudio-generator.jar

# Wir lassen das Fenster solange offen, bis wir enter gedrückt haben, damit wir sehen, ob es noch irgendwelche Fehlermeldungen gegeben hat
Read-host ‘Fertig! Drücke Enter zum Schließen des Fensters’