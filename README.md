# coinsws

This repo should be located at chronus:/var/www/html/coinsws

This application used by mrndata.jar (e.g. the DICOM Receiver) for converting unecrypted Subject Tag ID's to _encrypted_ Subject Tag ID's that are ultimately used for looking up URSI's. This is only used by scanners that identify participants using Subject Tags in DICOM files rather than URSI's in DICOM files (Wisconsin, Columbia). See the `externalSubjectID` setting in the DICOM Receiver conf file.

