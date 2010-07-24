<?php

/**
 * My Application
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */

use Nette\Debug,
	App\Models;

/**
 * Homepage presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class HomepagePresenter extends BasePresenter
{
	private $peoplesData = array(
		array('name' => "Zdeněk Němec", 'street' => "Jiřího Z Poděbrad 40", 'city' => "Kaplice", 'mail' => "nemec@seznam.cz"),
		array('name' => "Karel Moravec", 'street' => "Zborovská 11", 'city' => "Pohořelice", 'mail' => "karel.moravec@email.cz"),
		array('name' => "Josef Hájek", 'street' => "Skalka 44", 'city' => "Proseč", 'mail' => "josef.hajek@atlas.cz"),
		array('name' => "Kristina Krejčová", 'street' => "Horní Náměstí 48", 'city' => "Vejprnice", 'mail' => "krejcova@email.cz"),
		array('name' => "Daniela Zemanová", 'street' => "Foerstrova 29", 'city' => "Přibyslav", 'mail' => "zemanova@atlas.cz"),
		array('name' => "Radomír Musil", 'street' => "Blatenská 42", 'city' => "Hukvaldy", 'mail' => "musil@atlas.cz"),
		array('name' => "Jindřich Hájek", 'street' => "Ivana Olbrachta 37", 'city' => "Soběslav", 'mail' => "jindrich.hajek@centrum.cz"),
		array('name' => "Andrea Kučerová", 'street' => "U Stadionu 40", 'city' => "Teplá", 'mail' => "kucerova@seznam.cz"),
		array('name' => "Naděžda Kadlecová", 'street' => "Prokopa Velikého 4", 'city' => "Vroutek", 'mail' => "nadezda.kadlecova@centrum.cz"),
		array('name' => "Marie Pokorná", 'street' => "Náves 33", 'city' => "Lipová-lázně", 'mail' => "marie.pokorna@centrum.cz"),
		array('name' => "Gabriela Vaňková", 'street' => "Švédská 17", 'city' => "Oslavany", 'mail' => "gabriela.vankova@atlas.cz"),
		array('name' => "Vit Horák", 'street' => "Příční 17", 'city' => "Slušovice", 'mail' => "horak74@email.cz"),
		array('name' => "Patrik Beneš", 'street' => "Výletní 42", 'city' => "Olomouc", 'mail' => "benes55@seznam.cz"),
		array('name' => "Ludvík Král", 'street' => "Snp 1", 'city' => "Chropyně", 'mail' => "kral@email.cz"),
		array('name' => "Pavla Marešová", 'street' => "K Letišti 50", 'city' => "Blansko", 'mail' => "maresova@email.cz"),
		array('name' => "Jaroslav Svoboda", 'street' => "Hraniční 4", 'city' => "Jince", 'mail' => "svoboda@centrum.cz"),
		array('name' => "Ladislava Horáková", 'street' => "Čelakovského 47", 'city' => "Kozmice", 'mail' => "ladislava.horakova@centrum.cz"),
		array('name' => "Ivo Krejčí", 'street' => "Nám. Svobody 20", 'city' => "Fulnek", 'mail' => "krejci@email.cz"),
		array('name' => "Robert Vávra", 'street' => "28.Října 15", 'city' => "Boršice", 'mail' => "vavra@centrum.cz"),
		array('name' => "Jana Poláková", 'street' => "U Parku 44", 'city' => "Vejprnice", 'mail' => "polakova@atlas.cz"),
		array('name' => "Antonín Bartoš", 'street' => "Sadová 31", 'city' => "Studenec", 'mail' => "antonin.bartos@centrum.cz"),
		array('name' => "Natálie Čermáková", 'street' => "Trocnovská 21", 'city' => "Pozořice", 'mail' => "cermakova@email.cz"),
		array('name' => "Renata Kovářová", 'street' => "1. Máje 46", 'city' => "Jiříkov", 'mail' => "renata.kovarova@email.cz"),
		array('name' => "Jarmila Novotná", 'street' => "Třebízského 19", 'city' => "Trmice", 'mail' => "novotna@atlas.cz"),
		array('name' => "Dominika Burešová", 'street' => "Jezerní 4", 'city' => "Blatná", 'mail' => "dominika.buresova@seznam.cz"),
		array('name' => "Magdalena Sedláčková", 'street' => "Šumavská 25", 'city' => "Hovězí", 'mail' => "magdalena.sedlackova@centrum.cz"),
		array('name' => "Magdalena Říhová", 'street' => "Tylova 22", 'city' => "Litovel", 'mail' => "rihova@email.cz"),
		array('name' => "Pavlína Matoušková", 'street' => "Barvířská 39", 'city' => "Hroznětín", 'mail' => "matouskova@centrum.cz"),
		array('name' => "Ilona Krejčová", 'street' => "Hradišťská 27", 'city' => "Pěnčín", 'mail' => "krejcova55@email.cz"),
		array('name' => "Michael Kratochvíl", 'street' => "Tyršovo Náměstí 22", 'city' => "Krnov", 'mail' => "kratochvil@centrum.cz"),
		array('name' => "Bohumil Urban", 'street' => "Nám.Svobody 35", 'city' => "Solnice", 'mail' => "bohumil.urban@centrum.cz"),
		array('name' => "Rudolf Dostál", 'street' => "Českolipská 46", 'city' => "Podbořany", 'mail' => "rudolf.dostal@centrum.cz"),
		array('name' => "Stanislava Kopecká", 'street' => "Slunná 7", 'city' => "Úštěk", 'mail' => "stanislava.kopecka@centrum.cz"),
		array('name' => "Barbora Valentová", 'street' => "K Lesíku 36", 'city' => "Vroutek", 'mail' => "barbora.valentova@seznam.cz"),
		array('name' => "Robert Urban", 'street' => "Labská 4", 'city' => "Žatec", 'mail' => "robert.urban@atlas.cz"),
		array('name' => "Jaroslav Růžička", 'street' => "Zimní 12", 'city' => "Teplá", 'mail' => "jaroslav.ruzicka@email.cz"),
		array('name' => "Dominik Polák", 'street' => "Březinova 9", 'city' => "Nivnice", 'mail' => "polak@atlas.cz"),
		array('name' => "Renáta Horáková", 'street' => "Kopečná 23", 'city' => "Krnov", 'mail' => "horakova@centrum.cz"),
		array('name' => "Květuše Krejčová", 'street' => "Nemocniční 50", 'city' => "Návsí", 'mail' => "krejcova@centrum.cz"),
		array('name' => "Andrea Novotná", 'street' => "Benešovská 24", 'city' => "Ořechov", 'mail' => "novotna83@email.cz"),
		array('name' => "Pavel Tichý", 'street' => "Žižkova 24", 'city' => "Průhonice", 'mail' => "tichy@centrum.cz"),
		array('name' => "Ladislava Procházková", 'street' => "Kamenná 18", 'city' => "Koryčany", 'mail' => "prochazkova@centrum.cz"),
		array('name' => "Robert Zeman", 'street' => "Ústecká 32", 'city' => "Vlašim", 'mail' => "zeman@seznam.cz"),
		array('name' => "Vladimír Beneš", 'street' => "Prokopa Velikého 34", 'city' => "Mutěnice", 'mail' => "benes@email.cz"),
		array('name' => "Vladislav Sedláček", 'street' => "K Lesíku 47", 'city' => "Rudolfov", 'mail' => "vladislav.sedlacek@email.cz"),
		array('name' => "David Čech", 'street' => "Lázeňská 6", 'city' => "Frýdek-Místek", 'mail' => "cech@centrum.cz"),
		array('name' => "Karla Kučerová", 'street' => "Kolínská 46", 'city' => "Říčany", 'mail' => "karla.kucerova@atlas.cz"),
		array('name' => "Irena Kopecká", 'street' => "Kaplířova 24", 'city' => "Rudná", 'mail' => "irena.kopecka@centrum.cz"),
		array('name' => "Miloš Bureš", 'street' => "Fučíkova 12", 'city' => "Hořice", 'mail' => "milos.bures@seznam.cz"),
		array('name' => "Denisa Bláhová", 'street' => "Vrchlického 1", 'city' => "Hlinsko", 'mail' => "denisa.blahova@seznam.cz"),
		array('name' => "Jaroslav Dvořák", 'street' => "Sluneční 49", 'city' => "Praha", 'mail' => "dvorak@email.cz"),
		array('name' => "Anežka Doležalová", 'street' => "U Cukrovaru 37", 'city' => "Novosedlice", 'mail' => "anezka.dolezalova@centrum.cz"),
		array('name' => "Zdeňka Čechová", 'street' => "K Letišti 10", 'city' => "Mikulčice", 'mail' => "cechova66@centrum.cz"),
		array('name' => "Barbora Urbanová", 'street' => "U Plovárny 11", 'city' => "Sokolnice", 'mail' => "barbora.urbanova@atlas.cz"),
		array('name' => "Eduard Procházka", 'street' => "Rooseveltova 33", 'city' => "Odry", 'mail' => "prochazka@centrum.cz"),
		array('name' => "Anna Musilová", 'street' => "17. Listopadu 6", 'city' => "Bánov", 'mail' => "anna.musilova@atlas.cz"),
		array('name' => "Vojtěch Svoboda", 'street' => "Vojanova 27", 'city' => "Líně", 'mail' => "vojtech.svoboda@atlas.cz"),
		array('name' => "Svatopluk Zeman", 'street' => "Generála Svobody 39", 'city' => "Ledenice", 'mail' => "zeman@centrum.cz"),
		array('name' => "Olga Dostálová", 'street' => "Hradišťská 4", 'city' => "Znojmo", 'mail' => "olga.dostalova@centrum.cz"),
		array('name' => "Michal Navrátil", 'street' => "Zámečnická 39", 'city' => "Zdounky", 'mail' => "navratil80@centrum.cz"),
		array('name' => "Renáta Blažková", 'street' => "Za Kostelem 35", 'city' => "Ostrov", 'mail' => "renata.blazkova@seznam.cz"),
		array('name' => "Matěj Polák", 'street' => "Družstevní 39", 'city' => "Vřesina", 'mail' => "matej.polak@centrum.cz"),
		array('name' => "Michal Sedláček", 'street' => "Na Vrškách 19", 'city' => "Studená", 'mail' => "michal.sedlacek@seznam.cz"),
		array('name' => "Alena Vaňková", 'street' => "Úzká 24", 'city' => "Dolany", 'mail' => "vankova@centrum.cz"),
		array('name' => "Božena Krejčová", 'street' => "Výsluní 46", 'city' => "Chropyně", 'mail' => "krejcova@centrum.cz"),
		array('name' => "Martina Urbanová", 'street' => "Přímá 44", 'city' => "Volyně", 'mail' => "urbanova79@centrum.cz"),
		array('name' => "Františka Hájková", 'street' => "Pod Rozhlednou 43", 'city' => "Varnsdorf", 'mail' => "hajkova@atlas.cz"),
		array('name' => "Simona Kopecká", 'street' => "Za Kovárnou 4", 'city' => "Litvínov", 'mail' => "simona.kopecka@seznam.cz"),
		array('name' => "Arnošt Jelínek", 'street' => "K. Světlé 50", 'city' => "Průhonice", 'mail' => "jelinek@atlas.cz"),
		array('name' => "Kateřina Musilová", 'street' => "Meruňková 33", 'city' => "Peruc", 'mail' => "katerina.musilova@centrum.cz"),
		array('name' => "Marek Vlček", 'street' => "Slovenská 7", 'city' => "Plesná", 'mail' => "marek.vlcek@atlas.cz"),
		array('name' => "Rostislav Hájek", 'street' => "Zámek 50", 'city' => "Neveklov", 'mail' => "rostislav.hajek@atlas.cz"),
		array('name' => "Hynek Pokorný", 'street' => "Hutnická 4", 'city' => "Velešín", 'mail' => "hynek.pokorny@atlas.cz"),
		array('name' => "Alena Malá", 'street' => "K Lávce 43", 'city' => "Loučovice", 'mail' => "mala@centrum.cz"),
		array('name' => "Marek Polák", 'street' => "Zborovská 8", 'city' => "Libochovice", 'mail' => "polak@atlas.cz"),
		array('name' => "Anežka Horáková", 'street' => "Višňová 1", 'city' => "Borovany", 'mail' => "anezka.horakova@email.cz"),
		array('name' => "Břetislav Procházka", 'street' => "U Kaple 31", 'city' => "Praha", 'mail' => "bretislav.prochazka@centrum.cz"),
		array('name' => "Leoš Kopecký", 'street' => "Riegrova 3", 'city' => "Mohelnice", 'mail' => "kopecky@email.cz"),
		array('name' => "Ladislav Holub", 'street' => "Vojtěšská 1", 'city' => "Javorník", 'mail' => "holub@centrum.cz"),
		array('name' => "Alice Jelínková", 'street' => "Švédská 30", 'city' => "Vestec", 'mail' => "jelinkova@email.cz"),
		array('name' => "Břetislav Veselý", 'street' => "U Cihelny 26", 'city' => "Frýdlant", 'mail' => "bretislav.vesely@atlas.cz"),
		array('name' => "Vít Kovář", 'street' => "Jezerní 49", 'city' => "Paskov", 'mail' => "vit.kovar@centrum.cz"),
		array('name' => "Květuše Černá", 'street' => "Dlouhá 4", 'city' => "Chýnov", 'mail' => "kvetuse.cerna@centrum.cz"),
		array('name' => "Veronika Bartošová", 'street' => "Kaplířova 14", 'city' => "Děčín", 'mail' => "veronika.bartosova@atlas.cz"),
		array('name' => "Patrik Král", 'street' => "Malinová 9", 'city' => "Znojmo", 'mail' => "patrik.kral@email.cz"),
		array('name' => "Marie Malá", 'street' => "Holubova 22", 'city' => "Prostějov", 'mail' => "mala@email.cz"),
		array('name' => "Hana Soukupová", 'street' => "Ve Svahu 33", 'city' => "Hranice", 'mail' => "soukupova79@centrum.cz"),
		array('name' => "Adam Mareš", 'street' => "Bratří Čapků 3", 'city' => "Kvasice", 'mail' => "adam.mares@seznam.cz"),
		array('name' => "Přemysl Navrátil", 'street' => "Vojanova 14", 'city' => "Oslavany", 'mail' => "premysl.navratil@email.cz"),
		array('name' => "Viktor Kolář", 'street' => "Šmeralova 1", 'city' => "Hustopeče", 'mail' => "kolar@centrum.cz"),
		array('name' => "Zdenka Staňková", 'street' => "Na Blatech 6", 'city' => "Hrádek", 'mail' => "zdenka.stankova@centrum.cz"),
		array('name' => "Bohumil Kovář", 'street' => "Partyzánská 14", 'city' => "Ivančice", 'mail' => "kovar76@email.cz"),
		array('name' => "Libuše Nováková", 'street' => "Orlická 46", 'city' => "Lány", 'mail' => "libuse.novakova@atlas.cz"),
		array('name' => "Drahomíra Musilová", 'street' => "A. Dvořáka 35", 'city' => "Vamberk", 'mail' => "drahomira.musilova@email.cz"),
		array('name' => "Dominik Kříž", 'street' => "Jetelová 49", 'city' => "Rudník", 'mail' => "kriz78@email.cz"),
		array('name' => "Jaroslav Zeman", 'street' => "Břetislavova 46", 'city' => "Vroutek", 'mail' => "zeman@seznam.cz"),
		array('name' => "Vit Sýkora", 'street' => "Teplická 45", 'city' => "Chlumec", 'mail' => "sykora71@email.cz"),
		array('name' => "Milada Benešová", 'street' => "Muchova 11", 'city' => "Nepomuk", 'mail' => "milada.benesova@email.cz"),
		array('name' => "Silvie Kovářová", 'street' => "Nepomucká 31", 'city' => "Dolany", 'mail' => "kovarova@email.cz"),
		array('name' => "Zuzana Nováková", 'street' => "Křížová 24", 'city' => "Kopřivnice", 'mail' => "zuzana.novakova@email.cz")
	);

	public function renderDefault()
	{
		$this->template->message = 'We hope you enjoy this framework!';
	}
	
	private function randomNumber($max = 99, $min = 0)
	{
		mt_srand((double) microtime() * 1000000);
		return mt_rand($min, $max);
	}
	
	private function getRandomsNumbers($count, $max = 99, $min = 0)
	{
		$numbers = array();
		$i = 0;
		while ($i < $count) {
			$tmp = $this->randomNumber($max, $min);
			if (!in_array($tmp, $numbers)) {
				$numbers[] = $tmp;
				$i++;
			}
		}
		return $numbers;
	}
	
	private function process($action)
	{
		Debug::timer('total');
		
		switch ($action) {
			case 'select':
				$res = $this->processSelect();
				break;
			case 'insert':
				$res = $this->processInsert();
				break;
			case 'update':
				$res = $this->processUpdate();
				break;
			case 'delete':
				$res = $this->processDelete();
				break;
		}
	
		return array(number_format(Debug::timer('total') * 1000, 2), $res[0], $res[1]);
	}

	public function actionSelect()
	{
		list($this->template->totalExecution, $this->template->queryesExecution, $this->template->peoples) = $this->process('select');
		$this->setView('universal');
	}
	
	private function processSelect()
	{
		$ids = $this->getRandomsNumbers(1000, 50000, 1);
		$queryesExecution = array();
		$peoples = array();
		foreach ($ids as $id) {
			Debug::timer();
			// SELECT DATA
			$people = Models\People::find($id);
			$people->city;
			$peoples[] = $people;
			$queryesExecution[] = number_format(Debug::timer() * 1000, 2);
		}

		return array($queryesExecution, $peoples);
	}
	
	public function actionInsert()
	{
		list($this->template->totalExecution, $this->template->queryesExecution, $this->template->peoples) = $this->process('insert');
		$this->setView('universal');
	}

	private function processInsert()
	{
		$ids = $this->getRandomsNumbers(50);
		$queryesExecution = array();
		$peoples = array();
		foreach ($ids as $id) {
			Debug::timer();
			// INSERT DATA
			$people = $this->peoplesData[$id];
			$city = Models\City::findByName($people['city']);
			if ($city == NULL)
				$city = Models\City::create($people['city']);
			$peoples[] = Models\People::create($people['name'], $people['street'], $city, $people['mail'])->save();
			$queryesExecution[] = number_format(Debug::timer() * 1000, 2);
		}

		return array($queryesExecution, $peoples);
	}
	
	public function actionUpdate()
	{
		list($this->template->totalExecution, $this->template->queryesExecution, $this->template->peoples) = $this->process('update');
		$this->setView('universal');
	}

	private function processUpdate()
	{
		$ids = $this->getRandomsNumbers(50, 50000, 1);
		$ids2 = $this->getRandomsNumbers(50);
		$queryesExecution = array();
		$peoples = array();
		foreach ($ids as $key => $id) {
			Debug::timer();
			// SELECT + UPDATE DATA
			$people = Models\People::find($id);
			$city = Models\City::findByName($this->peoplesData[$ids2[$key]]['city']);
			if ($city == NULL)
				$city = Models\City::create($this->peoplesData[$ids2[$key]]['city']);
			$people->setName($this->peoplesData[$ids2[$key]]['name'])->setStreet($this->peoplesData[$ids2[$key]]['street'])
					->setCity($city)->setMail($this->peoplesData[$ids2[$key]]['mail']);
			$peoples[] = $people->save();
			$queryesExecution[] = number_format(Debug::timer() * 1000, 2);
		}

		return array($queryesExecution, $peoples);
	}
	
	public function actionDelete()
	{
		list($this->template->totalExecution, $this->template->queryesExecution, $this->template->peoples) = $this->process('delete');
		$this->setView('universal');
	}

	private function processDelete()
	{
		$ids = $this->getRandomsNumbers(50, 50000, 1);
		$queryesExecution = array();
		$peoples = array();
		foreach ($ids as $id) {
			Debug::timer();
			// SELECT + DELETE DATA
			$people = Models\People::find($id);
			$peoples[] = $people;
			$people->delete();
			$queryesExecution[] = number_format(Debug::timer() * 1000, 2);
		}

		return array($queryesExecution, $peoples);
	}

	protected function shutdown($response)
	{
		parent::shutdown($response);
		if (isset($this->template->totalExecution))
			file_put_contents(APP_DIR . "/log/" . $this->getAction() . ".log", date('r') . " @ " . $this->template->totalExecution 
					. " ms # " . number_format(memory_get_peak_usage() / 1000, 2) . "kB\r\n", FILE_APPEND);
	}
}
