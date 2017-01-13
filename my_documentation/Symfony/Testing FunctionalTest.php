Mit den Webtests kann man das Dokument durchcrawlen und wie mit QuerySelectoren 
absuchen:

Auf den Content kann man folgendermaßen zugreifen:
$client->getResponse()->getContent();

Im Content kann man nun mit den PHP-String-Funktionen und den Assertions bestimmte Tests machen.

Das HTML, die Tags, deren Inhalte und Eigenschaften untersucht man mit "filter".
"filter" ist wie ein querySelector:

Tutorial hierzu:
http://tutorial.symblog.co.uk/docs/testing-unit-and-functional-phpunit.html
https://symfony.com/doc/current/testing.html

Um zu zählen, wie viele h1-Tags es mit dem Inhalt "hinzufügen" gibt:
$crawler->filter('h1:contains("hinzufügen")')->count()
Das gilt aber nicht für die Parameter des Tags selbst, z.B. styles usw.!

Zählen, wie oft der Tag "input" vorkommt:
$crawler->filter('input')->count();

Testen, ob die Flash-Meldung mit der CSS-Klasse .blogger-notice mit dem Inhalt "Your contact enquiry was successfully sent. Thank you!" 
vorkommt:
$this->assertTrue($crawler->filter('.blogger-notice:contains("Your contact enquiry was successfully sent. Thank you!")')->count() > 0);

Wie oft ein Element mit der Id="form__token" vorkommt:
$crawler->filter('#form__token')->count();

Wie oft kommt ein Tag mit dem Attribut value (in Input-Tags) vor?
$crawler->filter('[value]')->count();

Wie oft kommt ein Tag namens "Button" innerhalb von "form"-Tags vor?
$crawler->filter('form button')->count()

Verschachtelter Test:
$this->assertEquals('name', $crawler->filter('aside.sidebar section')->last()
    ->filter('article')->first()
    ->filter('header span.highlight')->text()
    ->filter('header span.lowlight')->parents()
);

DB-Tests, um Resultate zu prüfen:

Die Funktionen müssen in der Reihenfolge geschrieben werden, in der sie ausgeführt 
werden sollen:

1. Dazu in setUp() den Entity-Manager laden:

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

2. Danach die Funktionen schreiben, die ein Ergebnis erzeugen sollen:

    public function testForm()
    {
        echo "In der TestForm-Funktion!\n";
        $client = static::createClient();
        $crawler = $client->request('GET', '/forms/add_product');

        $this->assertGreaterThan(
            0,
            $crawler->filter('form')->count()
        );
        $this->assertGreaterThan(
            2,
            $crawler->filter('input')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('form button')->count()
        );
    }

3. Nun die Tests für die Ergebnisse, z.B. Datenbanktests, einfügen und vorher mit @depends 
angeben, dass eine oder bestimmte andere Funktion/en vorher ausgeführt worden sein musste/n.
 
Wenn die benötigte Funktion/en, die in @depends steht/en, erst danach deklariert wird/werden, 
wird testResult() nicht ausgeführt und eine Warnung ausgegeben, weil die Funktion/en in der 
Bedingung vorher deklariert sein musste/n!

    /**
    * @depends testFillOutForm
    * @depends testXyz
    */
    public function testResult()
    {
        $products = $this->em 
            ->getRepository('AppBundle:Product')
            ->findAll()
        ;

        /**
        * Anzahl der Datensätze:
        */
        $this->assertGreaterThan(
            3,
            count($products)
        );
    }

4. Es können auch in den funktionalen Tests "normale" Funktionen, die nicht über das Web 
aufgerufen werden, getestet werden, WENN man den Entity-Manager mitgibt:

    public function testGetProducts()
    {
        $test = new FormsController();
        $products = $test->getProducts($this->em);
        $this->assertGreaterThan(
            2,
            count($products)
        );
    }

In den "normalen" Funktionen darf nur der mitgegebene Entity-Manager benutzt werden, 
sonst wirft der Test eine Fehlermeldung aus! In den über den Webclient aufgerufenen Funktionen 
kann der Entity Manager ganz normal in der Funktion geladen werden.

Beispiele:

<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormsControllerTest extends WebTestCase
{
    protected $em;

    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testForm()
    {
        echo "In der TestForm-Funktion!\n";
        $client = static::createClient();
        $crawler = $client->request('GET', '/forms/add_product');

        $this->assertGreaterThan(
            0,
            $crawler->filter('form')->count()
        );
        $this->assertGreaterThan(
            2,
            $crawler->filter('input')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('form button')->count()
        );
    }

    public function testGetProducts()
    {
        $test = new FormsController();
        $products = $test->getProducts($this->em);
        $this->assertGreaterThan(
            2,
            count($products)
        );
    }

    /**
    * @depends testForm
    */
    public function testResult()
    {
        echo "In der TestResult-Funktion!\n";
        $products = $this->em 
            ->getRepository('AppBundle:Product')
            ->findAll()
        ;
        echo "Anzahl Datensaetze: " . count($products) . "\n";
    }

    public function testGetProducts()
    {
        $test = new FormsController();
        $products = $test->getProducts($this->em);
        $this->assertGreaterThan(
            2,
            count($products)
        );
    }
}

Oder ein anderes Skript:

<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTest extends WebTestCase
{
    protected function setUp()
    {
        // Falls vorhanden, wird diese Methode anfangs aufgerufen zum Initialisieren der Testumgebung
    }

    public function testGetValueAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/testing/get_value');

        $this->assertGreaterThan(
            999,
            $client->getResponse()->getContent()
        );
    }

    public function testGetHtml()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/testing/get_html');

        $this->assertCount(3, $crawler->filter('div'));
    }
}
