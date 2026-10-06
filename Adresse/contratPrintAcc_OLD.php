<?php
session_start();
ob_start();
include_once "../src/class/Db.class.php";
include_once "../src/class/Mrp.class.php";
$mrp = new Mrp();
include('../variables.php');
$bdd = new PDO($dsn, $user, $password);
$id = $_GET['Id'];




$contrat = $bdd->query("SELECT * FROM tblContratAcc
LEFT JOIN tblTypeAcc on contType = tAccId WHERE contId ='$id'");
$contrat = $contrat->fetch();
$employer = $contrat['conId'];
$contact = $bdd->query("SELECT conNpa, conLocaliter, conNom, conPrenom, conDateNaissance,conAdresse, conAvs,natNom,
 conBanque, conAgence, conIban FROM tblContact
  LEFT JOIN tblNationaliter on conNationalite = natId WHERE conId = '$employer'");
$contact = $contact->fetch();

use Spipu\Html2Pdf\Html2Pdf;




?>
    <style>
        * {

            margin: 0;
            padding: 0;
            color: #000;
            font-family: "helvetica", sans-serif;

        }

        table {

            width: 100%;
            color: #9A0000;

        }

        td {

            vertical-align: middle;
            text-align: left;
            padding-right:5mm ;

        }

        th {

            vertical-align: middle;
            text-align: left;
            color: #00AA33;
            font-size: 12px;
            font-style: normal;
            font-weight: normal !important;
        }

        .footer td {
            vertical-align: bottom
        }

        h1 {
            width: 100%;
            text-align: right;
            font-size: 20px;

        }

        h2 {

            color: #000;
            margin-top: 5mm;
            margin-bottom: 5mm;
            font-size: 14px;
            padding-left: 3mm;
        }

        p {
            text-align: justify;
            margin-bottom: 3mm;


        }


    </style>
    <page backtop="8mm" backbottom="30mm" backleft="15mm" backright="5mm">
    <page_header>
        <table>
            <tr>
                <td style=" vertical-align: top;  padding-left: 5mm"><img style="width: 205mm" src="../img/papier_logo.gif"></td>

            </tr>
        </table>
    </page_header>




        <h1 style="margin-top:-4mm;"><?php echo $mrp->getText("CONTRAT DE TRAVAIL") ?> <br><br></h1>
        <h2 style="margin-top:-4mm; text-align: right"><?php echo $mrp->getText($contrat['tAccNom']) ?> - <?php echo $mrp->getText("Année") ?> <?php echo $contrat['contAnnee']; ?>  </h2>


        <p style="margin-top: 20mm"> <strong><?php echo $mrp->getText("Entre") ?> :</strong> L'Association Cerebral Valais </p>

        <p > <strong><?php echo $mrp->getText("Et") ?>:</strong> <?php echo $mrp->getText("L'employé(e)"); ?> </p>
        <table style="margin-bottom: 10mm">
            <tr>
                <td><?php echo $mrp->getText("Prénom")."/".$mrp->getText("Nom"); ?></td>
                <td><strong><?php echo $contact['conPrenom'].' '.$contact['conNom']?></strong></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Date de naissance"); ?></td>
                <td><strong><?php echo dateToUser($contact['conDateNaissance'])?></strong></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Lieu d'origine / Nationalité"); ?></td>
                <td><strong><?php echo $contact['natNom']?></strong></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Adresse"); ?></td>
                <td><strong><?php echo $contact['conAdresse'].' '.$contact['conNpa'].' '.$contact['conLocaliter']?></strong></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("N° AVS"); ?></td>
                <td><strong><?php echo $contact['conAvs']?></strong></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Coordonnées bancaires (nom et lieu)") ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td><strong><?php echo $contact['conBanque'].' '.$contact['conAgence']?></strong></td>
            </tr>
            <tr>
                <td><?php echo $mrp->getText("Compte IBAN") ?></td>
                <td><strong><?php echo $contact['conIban']?></strong></td>
            </tr>

        </table>
        <p>L'Association Cerebral Valais <?php echo $mrp->getText("engage") ?> <?php echo $contact['conPrenom'].' '.$contact['conNom']?>
            <?php echo $mrp->getText("en qualité de") ?> <?php echo $mrp->getText($contrat['tAccNom'] ); ?> <?php echo $mrp->getText("aux conditions suivantes") ?>: </p>

  <?php
   if ($mrp->language=="fr")
	{
	?>
        <p> 1. L'employé(e) entre au service de l'Association Cerebral Valais pour
            l'année <?php echo $contrat['contAnnee']; ?> </p>
        <p> 2. Les jours de travail effectifs et la tâche de l’employé(e) sont stipulés sur la feuille de route relative
            à l’activité. &nbsp;&nbsp;&nbsp;&nbsp;Ce contrat reste en vigueur pour tous les week-ends de l’année. </p>


            <?php if ($contrat['contType']== 1){?>
        <p>3. Le salaire brut de base s’élève à CHF. <?php echo $contrat['contSalaireBruit']?> / jour, comme <?php echo $contrat['tAccNom']?>
                et à CHF. <?php echo $contrat['contSalaireChauffeur']?> / jour comme &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;accompagnant(e) et chauffeur, part de vacances comprise.
            Ce défraiement est donc variable selon la tâche &nbsp;&nbsp;&nbsp;&nbsp;effectuée durant le week-end. <?php;}
            else {?>
        <p>3. Le salaire brut de base s’élève à CHF. <?php echo $contrat['contSalaireBruit']?> / jour, comme <?php echo $contrat['tAccNom']?>,
                part de vacances comprise. Ce &nbsp;&nbsp;&nbsp;&nbsp;défraiement est donc variable selon la tâche effectuée durant le week-end, <?php;}?>
            <br> &nbsp;&nbsp;&nbsp;&nbsp;Les cotisations pour
            l’AVS/AI/APG/AC et pour les accidents non-professionnels sont déduites du salaire brut.</p>

        <p> 4. L’employé(e) est assuré(e) par l’employeur contre les accidents professionnels, y compris ceux survenant
            sur le &nbsp;&nbsp;&nbsp;&nbsp;chemin direct entre le domicile et le lieu de travail.</p>
        <p> 5. En cas de transport de personnes, l’assurance propre du détenteur du véhicule intervient (RC
            occupants)</p>
        <p> 6. Les frais de transport du domicile au lieu de séjour sont pris en charge par l’Association Cerebral
            Valais pour &nbsp;&nbsp;&nbsp;&nbsp;autant que l’employé(e) accompagne au moins un participant.</p>
        <p> 7. En cas de faute grave, l’employeur peut licencier l’employé(e) avec effet immédiat.</p>
        <p> 8. Pour tout ce qui n’est pas prévu dans le présent contrat, le code des obligations et les lois fédérales
            et &nbsp;&nbsp;&nbsp;&nbsp;cantonales sur le travail sont applicables.</p>
        <p> 9. L’employé(e) s’engage à garder le secret sur toutes les informations auxquelles il (elle) a accès de par
            sa &nbsp;&nbsp;&nbsp;&nbsp;fonction.</p>

		<?php
		}


	?>
		
	<!--- START OF DE -->
	
	<?php
	
	if ($mrp->language=="de")
	{
	
	?>

<p><strong>1. Die Leistungen des Betreuungsdienstes</strong></p>
<p>
Der Betreuungsdienst der Vereinigung Cerebral Wallis kann von Menschen mit einer Beeinträchtigung in Anspruch genommen werden, die hauptsächlich zu Hause wohnen – unabhängig davon, ob sie Sozialleistungen des Kantons Wallis beziehen oder nicht.
  </p>
  <p>
Das Ziel des Betreuungsdienstes ist es, Menschen mit einer Beeinträchtigung punktuell in ihrem Zuhause zu begleiten und zu unterstützen. Dadurch sollen pflegende und betreuende Angehörige entlastet werden. Die Leistungen umfassen die Unterstützung bei der Teilhabe am gesellschaftlichen Leben (Hilfe beim Ankleiden, beim Essen, bei der Körperpflege oder Begleitung bei Aktivitäten ausserhalb der Wohnung).
Diese durch den Sozialdienst subventionierte Hilfe ist pro Person mit Beeinträchtigung auf ein Maximum von 200 Stunden pro Jahr festgelegt. Die Betreuung wird von Helfer*innen übernommen ohne entsprechende fachliche Qualifikation. Allerdings erhalten die eingesetzten Helfer*innen durch die Vereinigung Cerebral eine Einführung und die erforderlichen Instruktionen für die Betreuung von Menschen mit einer Beeinträchtigung. Die Vereinigung überprüft die Vertrauenswürdigkeit der Helfer*innen durch das Einfordern eines Strafregisterauszugs. 
 </p>
 
<p><strong>2. Grenzen des Betreuungsdienstes</strong></p>
<p>
Der Betreuungsdienst ist nicht als Ersatz zu bestehenden Einrichtungen zu verstehen. So darf ein Betreuungseinsatz pro Person nicht länger als 12 aufeinanderfolgende Stunden dauern. 
Menschen mit einer Beeinträchtigung, die während der Woche in einer Einrichtung leben und nur am Wochenende nach Hause kommen, sind von diesem Betreuungsangebot in der Regel ausgeschlossen. In Ausnahmesituationen kann der Betreuungsdienst kontaktiert werden und eine mögliche Lösung wird gesucht.
</p>
<p>
Das Betreuungsangebot kann tagsüber oder abends in Anspruch genommen werden und ist jeweils Teil einer zeitlich begrenzten Intervention. Die zur Verfügung stehende Betreuungszeit ist unabhängig vom Grad der Hilflosigkeit der zu betreuenden Personen.
Aufgaben wie Haushaltshilfe, Versorgung von Tieren (Ausnahmen sind Blinden- und Begleithunde), Transport, medizinische Pflege sowie Betreuung von Geschwistern fallen nicht in den Leistungskatalog des Begleitdienstes.
Die Anfrage für einen Betreuungsdienst erfolgt mindestens 10 Tage im Voraus. Andernfalls kann die Verfügbarkeit der Helferin / des Helfers und damit die gewünschte Betreuung nicht gewährleistet werden.
</p>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<p><strong>3. Kosten</strong></p>

<p>

Die Kosten betragen netto CHF 25.- pro Stunde und werden durch die Vereinigung Cerebral Wallis dem Leistungsempfänger direkt in Rechnung gestellt, sofern die Person mit einer Beeinträchtigung (untenstehend als Klient*in bezeichnet) kein Anrecht auf finanzielle Unterstützung hat.

Für Klient*innen, die Sozialhilfe erhalten, betragen die Kosten netto CHF 8.- pro Stunde und werden durch die Vereinigung Cerebral Wallis dem Leistungsempfänger direkt in Rechnung gestellt. Die restlichen CHF 17.- pro Stunden werden der Dienststelle für Sozialwesen verrechnet.

Die gesetzliche Vertretung ist dafür verantwortlich, die über den Sozialdienst festgelegte Anzahl Jahresstunden zu überwachen. Sind die bewilligten Stunden ausgeschöpft, müssen weiteren Betreuungsstunden zum Tarif von CHF 25.- pro Stunde dem Leistungsempfänger in Rechnung gestellt werden. 

Kann ein Betreuungseinsatz zum geplanten Zeitpunkt nicht erfolgen, muss die Koordinationsperson umgehend informiert werden. Der Betrag eines geplanten und weniger als 24 Stunden im Voraus annullierten Einsatzes bleibt dem Helfer / der Helferin geschuldet.

Die von den Helfenden während des Betreuungseinsatzes eingenommenen Mahlzeiten zu Hause und extern gehen auf Kosten der Person mit einer Beeinträchtigung.
</p>

<p><strong>4. Anfrage</strong></p>

<p>
Bei einer Anfrage wird in einem ersten Schritt versucht, eine Kostengutsprache des Sozialdienstes für maximal 200 Betreuungsstunden pro Jahr zu erhalten. 
Nach Genehmigung werden die Betreuungsstunden unter der Koordination von Cerebral Wallis zugeteilt.
Der gesamte Ablauf der Anfrage präsentiert sich wie folgt:
Die Anfrage wird durch die Koordinationsperson von Cerebral Wallis verfasst. Sie beinhaltet das Antragsformular mit den Angaben zur Art der Beeinträchtigung, zur Hilflosenentschädigung, zum Anrecht auf Ergänzungsleistungen, zu kantonalen Finanzierungshilfen sowie mit den Personalien des / der Antragsstellenden. Das Gesuch enthält weiter die unterschriebene Vereinbarung zwischen der Vereinigung Cerebral Wallis und des Klienten / der Klientin oder ihrer gesetzlichen Vertretung;
der Sozialdienst fällt eine Entscheidung aufgrund des eingereichten Antrags zum Bezug von Betreuungsleistungen zu Hause. Dieser Entscheid hat eine Gültigkeit von 12 Monaten für ein jährliches Stundenmaximum von 200 Stunden;
sobald der Entscheid vorliegt, kann die Person mit einer Beeinträchtigung zusammen mit Cerebral Wallis die benötigte Betreuung organisieren;
die Person mit einer Beeinträchtigung ist verpflichtet, während der 12-monatigen Verfügung auftretende Veränderung, die deren Gewährung beeinflussen, unverzüglich Cereral Wallis und dem Sozialdienst zu melden;
die Vereinigung Cerebral Wallis stellt die Helferinnen und Helfer an;
basierend auf einer Übersichtstabelle mit den Betreuungsleistungen fakturiert Cerebral Wallis die anfallenden Kosten sowohl an die Person mit einer Beeinträchtigung als auch an die Dienststelle für Sozialwesen (Koordinationsstelle für soziale Leistungen); 
die Erneuerung der Verfügung kann über Cerebral Wallis bei der Dienststelle für Sozialwesen beantragt werden. Für diesen Antrag auf Weiterführung müssen dieselben Dokumente wie beim Anfangsgesuch eingereicht werden.
 </p>	

<p>
5. <strong>Organisation und Koordination des Betreuungsdienstes</strong>
</p>
<p>
Eine Koordinationsperson leitet den Betreuungsdienst. Sie ist für die operative und finanzielle Leitung sowie für die personellen Aufgaben (Ausbildung, Führung und Begleitung der Helferinnen und Helfer) zuständig. Als Kontaktperson zu den Personen mit einer Beeinträchtigung nimmt sie Anfragen, Vorschläge und Reklamationen entgegen, erkennt Probleme und bearbeitet diese umgehend.
Sie gewährleistet die Zusammenarbeit zwischen der Person mit einer Beeinträchtigung, der Helferin / dem Helfer und der Dienststelle für Sozialwesen.
Die Helfer*innen übernehmen die Betreuung der Person mit einer Beeinträchtigung. Sie sind über die Vereinigung Cerebral Wallis angestellt. Die Helfer*innen müssen ihre Einsatzpläne jeweils bis zum 20. des Monats bei der Cerebral abgeben. 
</p>
<p>
Familien/Personen mit einer Beeinträchtigung, die Helfer*innen von Cerebral nicht über den Betreuungsdienst engagieren, haben kein Anrecht auf entsprechende Koordination und finanzielle Unterstützung durch den Sozialdienst, die Entlöhnung der Helfer*innen ist nicht gewährleistet. Die entstandenen Kosten haben vollumfänglich die Familie oder die Person mit Beeinträchtigung zu tragen. 
</p>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<p><strong>6. Ablauf der Betreuungseinsätze</strong></p>
<p>
Der erste Kontakt zwischen der Person mit einer Beeinträchtigung oder ihrer gesetzlichen Vertretung und der Koordinationsperson des Betreuungsdienstes ermöglicht das gegenseitige Kennenlernen und das Austauschen wichtiger Informationen für eine gelingenden Betreuung. Bei Übereinkunft wird die Vereinbarung zur Regelung der Modalitäten für die Betreuungseinsätze unterzeichnet.
</p>
<p>
Ein Formular mit den erforderlichen Informationen und Angaben zur Person mit einer Beeinträchtigung wird ausgefüllt und der Helferin / dem Helfer zur Verfügung gestellt.
Die vorgesehenen Betreuungsstunden müssen im Voraus Cerebral Wallis gemeldet werden.
Die Koordinationsperson führt eine Liste mit den gemeldeten Betreuungsanfragen der Personen mit einer Beeinträchtigung oder der gesetzlichen Vertretung und der Verfügbarkeit der Helfer*innen.
Die Person mit einer Beeinträchtigung oder deren gesetzliche Vertretung entscheidet abschliessend, ob es zum Einsatz der von der Koordinationsperson vorgeschlagenen Helferin / dem Helfer kommt. 
Der Zeitpunkt und die Dauer der Betreuung oder eine Annulation werden durch die Person mit einer Behinderung oder durch deren gesetzliche Vertretung bestimmt.
Aktivitäten, die von der Person mit einer Beeinträchtigung oder von deren gesetzlichen Vertretung verlangt werden und bei denen zusätzliche Kosten anfallen (Schwimmbad, Kino, Restaurant, …), gehen vollumfänglich zu Lasten der Person mit einer Beeinträchtigung (das gilt für die Kosten der Person mit einer Beeinträchtigung und der Helferin / des Helfers).
Der Transport von Personen mit einer Beeinträchtigung gehört nicht zum Pflichtenheft der Helfer*innen. Helfer *innen können einen Transport übernehmen, falls sie durch die Person mit einer Behinderung oder durch deren gesetzliche Vertretung angefragt, beauftragt und mit CHF 0.70/km entschädigt werden. In diesem Fall obliegt die Verantwortlichkeit nicht Cerebral.
Am Ende einer Betreuungssituation überprüft und unterschreibt die Person mit einer Beeinträchtigung oder deren gesetzliche Vertretung das Betreuungsformular mit den aufgelisteten Einsätzen. Die Helfer*innen geben das Formular bei der Koordinationsstelle von Cerebral ab. Dadurch wir die Fakturierung sichergestellt.
</p>
<p>
Periodisch findet ein Auswertungsgespräch zwischen der Person mit einer Beeinträchtigung (gesetzliche Vertretung), der Koordinationsperson und er Helferin / dem Helfer statt. 
Die Rückmeldungen fliessen in die aktuelle Betreuungsvereinbarung ein.
Mit ihrer Unterschrift anerkennen die Partner die Betreuungsvereinbarung. Sie informieren sich gegenseitig über allfällige Veränderungen.
Zur Kenntnis genommen und unterschrieben, den ___________________________________
</p>

	<?php 
	
	}
	?>
	<!--- END OF DE --->

        <p style="text-align: Left; margin-top: 10mm; margin-left: 108mm"><?php echo $mrp->getText("Lieu")." ".$mrp->getText("et")." ".$mrp->getText("date"); ?>: <br>...............................................<br><br>
            <?php echo $mrp->getText("Pour l’Association Cerebral Valais") ?><br> <?php echo ('Bruno PERROUD')?> </p>
        <p style="text-align: left; margin-top: -22mm; margin-bottom: 5mm"><?php echo $mrp->getText("Lieu")." ".$mrp->getText("et")." ".$mrp->getText("date"); ?> : <br>.....................................................<br><br>
             <?php echo $mrp->getText("Pour l' employé(e)") ?> <br><?php echo ($contact['conPrenom'].' '.$contact['conNom'])?></p>



        <p style><?php echo $mrp->getText("Signature") ?>:</p>

        <p style="text-align: right; margin-right: 56mm;margin-top: -6mm"><?php echo $mrp->getText("Signature") ?>:</p>

    </page>
<page_footer>
    <p style="color: #00AA00; font-size: 10px; text-align: center">
        Association Cerebral Valais&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        Tél. 027 346 70 44&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        www.cerebral-valais.ch&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CH10 8057 2000 0099 9224 8
       ___________________________________________________________________________________________________________________</p>
    <p style="font-size: 10px; text-align: center">En étroite collaboration avec l’association Cerebral Suisse et la Fondation suisse en faveur de l’enfant infirme moteur cérébral<br>
        In enger Zusammenarbeit mit der Vereinigung Cerebral Schweiz und der Schweizerischen Stiftung für das cerebral gelähmte Kind</p>

</page_footer>



<?php

try {
    $content = ob_get_clean();
    require _('../vendor/autoload.php');
    $family = 'coucou';
    $style = 'regular';
    $file = '../vendor/tecnickcom/tcpdf/fonts/helvetica.php';
    $pdf = new HTML2PDF('P', 'A4', 'fr');
    $pdf->pdf->SetDisplayMode('fullwidth', 'tworight');

    $pdf->writeHTML($content);

    $pdf->addFont($family, $style, $file);
ob_get_clean();
    $pdf->output('ContratIntervenant.pdf');
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    die($e);
};
