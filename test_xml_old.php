<?php
/*
 Template Name: XML Testseite
 Template Post Type: page
*/
get_header(); ?>
<!-- main content-->
<?php 
/* CURLAUTH_BASIC pw+nutzer (-u)
CURLOPT_HTTPHEADER  header (-H)
CURLOPT_URL (-X)
CURLOPT_POSTFIELDS data json encoded (-d)

curl -X POST https://example.com/wp-json/wc/v3/products \
    -u consumer_key:consumer_secret \
    -H "Content-Type: application/json" \
    -d '{
  "name": "Premium Quality",
  "type": "simple",
  "regular_price": "21.99",
  "description": "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.",
  "short_description": "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.",
  "categories": [
    {
      "id": 9
    },
    {
      "id": 14
    }
  ],
  "images": [
    {
      "src": "http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg"
    },
    {
      "src": "http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg"
    }
  ]
}'
*/

function xml2assoc($xml, $name)
{ 

    $tree = null;

  
    while($xml->read()) 
    {
        if($xml->nodeType == XMLReader::END_ELEMENT)
        {
            return $tree;
        }
        
        else if($xml->nodeType == XMLReader::ELEMENT)
        {
            $node = array();
            
            $node['tag'] = $xml->name;

            if($xml->hasAttributes)
            {
                $attributes = array();
                while($xml->moveToNextAttribute()) 
                {
                    $attributes[$xml->name] = $xml->value;
                }
                $node['attr'] = $attributes;
            }
            
            if(!$xml->isEmptyElement)
            {
                $childs = xml2assoc($xml, $node['tag']);
                $node['childs'] = $childs;
            }
            
            $tree[] = $node;
        }
        
        else if($xml->nodeType == XMLReader::TEXT)
        {
            $node = array();
            $node['text'] = $xml->value;
            $tree[] = $node;

        }
    }
    
    return $tree; 
}
/* creating the DomDocument and set it clean */
$dom = new DOMDocument;
$dom->preserveWhiteSpace = false;

/* loading the file */
$dom->Load('https://actetrestorage.blob.core.windows.net/export/articles.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');

/* preparing the xpath for the dom doc */
$xpath = new DOMXPath($dom);

/* query just the attributes from the xml document */
$query = '//Product';
$items = $xpath->query($query);
$i = 0;
/* going thru each item */
$articles[] = [];

foreach ($items as $item) {        
    $nodes = $item->childNodes;
    $j= 0;
    foreach ($nodes as $node) {

      echo $node->nodeName. ": ";
      
      if($node->nodeName == "Option"){
        $subnodes = $node->childNodes;
        foreach ($subnodes as $subnode) {
          $articles[$i][$node->nodeName][$j][$subnode->nodeName] = $subnode->nodeValue;
          echo "<br>".$subnode->nodeName. ": ";
          echo $subnode->nodeValue. "<br>";
          
        }
      }else{
        $articles[$i][$node->nodeName] = $node->nodeValue;
        echo $node->nodeValue. "<br>";$j=0;
      }
      $j++;
      
    }
    
    /* echo "ITEM: ".$item->nodeName."<br>";
    echo "ITEM: ".$item->nodeValue."<br>"; */
    if($i >100)break;
    $i++;
}
echo "<pre>".var_export($articles,true)."</pre>";
/* echo "<PRE>";

$xml = new XMLReader(); 
$xml->open('https://actetrestorage.blob.core.windows.net/export/articles.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D'); 
$assoc = xml2assoc($xml, "root"); 
$xml->close();

echo var_export(($assoc),true);
echo "</PRE>"; */
function get_xml_from_url($url){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

    $xmlstr = curl_exec($ch);
    curl_close($ch);

    return $xmlstr;
}
function create_woo_product_per_api($url){

    $key = "ck_1523ea8cecd9458b7e54933320ccd4a506921a6d";
    $secret = "cs_42fa83dac37f88d3830f37854dd4fdec106d9f5a";

    $json_string = '{
        "name": "Test Product Outline",
        "type": "simple",
        "regular_price": "21.99",
        "description": "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.",
        "short_description": "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.",
        "sku": "AB12345",
        "categories": [
          {
            "id": 18
          },
          {
            "id": 23
          }
        ],
        "images": [
          {
            "src": "https://outline2023.enpr.de/wp-content/uploads/2023/06/maxresdefault.jpg"
          },
          {
            "src": "https://outline2023.enpr.de/wp-content/uploads/2023/06/messe.png"
          }
        ]
      }';

    $ch = curl_init();
 $header['Content-Type'] = 'application/json;charset=utf-8';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$key:$secret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
    

    $xmlstr = curl_exec($ch);
    curl_close($ch);

    return $xmlstr;//"Product importiert!";
}

function update_woo_product_per_api($url,$product_id){

  $key = "ck_1523ea8cecd9458b7e54933320ccd4a506921a6d";
  $secret = "cs_42fa83dac37f88d3830f37854dd4fdec106d9f5a";
  //$url = $url."/".$product_id;
  $json_string = '{
      "regular_price": "33.99",
      "description": "Irgendeine geänderte Produktbeschreibung.",
      "short_description": "Geänderte Produktkurzbeschreibung Test."
    }';

  $ch = curl_init();
$header['Content-Type'] = 'application/json;charset=utf-8';
  curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  /*   curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
  curl_setopt($ch, CURLOPT_PUT, 1); */
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, "$key:$secret");
/*   curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string); */
  

  $xmlstr = curl_exec($ch);
  curl_close($ch);

  return $xmlstr;//"Product importiert!";
}
/* $xmlstr = get_xml_from_url('https://actetrestorage.blob.core.windows.net/export/categories.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');
$xmlobj = new SimpleXMLElement($xmlstr);

//echo "<pre>".var_export($xmlobj,true)."</pre>";

$xmlstr2 = get_xml_from_url('https://actetrestorage.blob.core.windows.net/export/articles.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');
$xmlobj2 = new SimpleXMLElement($xmlstr2);
$xmlobj2a = (array)$xmlobj2;

/* echo "<pre>".var_export($xmlobj2,true)."</pre>"; 

$xmlstr3 = get_xml_from_url('https://actetrestorage.blob.core.windows.net/export/customers.xml?sv=2021-12-02&ss=b&srt=co&sp=rltf&se=2030-01-01T01:45:04Z&st=2023-03-18T17:45:04Z&spr=https&sig=I2ltq8oLMDhhlOFkYQ62bvdgcwBnb76mdHreKsgkYZ8%3D');
$xmlobj3 = new SimpleXMLElement($xmlstr3); */

//echo "<pre>".var_export($xmlobj3,true)."</pre>";

	if ( have_posts() ) : 
	// Do we have any posts/pages in the databse that match our query?
		while ( have_posts() ) : the_post(); 
		?>

		<main>
<?php //echo "<pre>".var_export($xmlobj->Category[1],true)."</pre>";
/* $strCatsHTML = "";
    foreach($xmlobj->Category as $cat){
        $strCatsHTML .= "<div style='border: 2px solid rebeccapurple;'>
                            <p>
                                ID: ".$cat->Id."</br>
                                Key: ".$cat->Key."</br>
                                Category: ".$cat->Category."</br>
                                Parent: ".$cat->Parent."</br>
                                ParentKey: ".$cat->ParentKey."</br>
                            </p>
                        </div>";
    }
//echo $strCatsHTML;
$strCustomerHTML = "";
    foreach($xmlobj3->Customer as $cat){
        $strCustomerHTML .= "<div style='border: 2px solid rebeccapurple;'>
                            <p>
                                Nummer: ".$cat->Nummer."</br>
                                Email: ".$cat->Email."</br>
                                Category: ".$cat->Category."</br>
                                PLZ: ".$cat->PLZ."</br>
                                Firma: ".$cat->Firma."</br>
                                Land: ".$cat->Land."</br>
                            </p>
                        </div>";
    }
//echo $strCustomerHTML;
$strProductHTML = "";
    foreach($xmlobj2->Product as $cat){
        $strProductHTML .= "<div style='border: 2px solid rebeccapurple;'>
                            <p>
                                Barcode: ".$cat->Barcode."</br>
                                SKU: ".$cat->SKU."</br>
                                Bezeichnung: ".$cat->Bezeichnung."</br>
                                Beschreibung: ".$cat->Beschreibung."</br>
                                Zusatz: ".$cat->Zusatz."</br>
                                Land: ".$cat->Land."</br>
                                Listenpreis: ".$cat->Listenpreis."</br>
                                Available: ".$cat->Available."</br>
                                Künstlervorname: ".$cat->Künstlervorname."</br>
                                Künstlernachname: ".$cat->Künstlernachname."</br>
                                Title: ".$cat->Title."</br>
                                SEOKeywords: ".$cat->SEOKeywords."</br>
                                SEODescription: ".$cat->SEODescription."</br>
                                Papierbreite: ".$cat->Papierbreite."</br>
                                Papierhöhe: ".$cat->Papierhöhe."</br>
                                VPE: ".$cat->VPE."</br>
                                Vertriebsgebiet: ".$cat->Title."</br>
                                Category: ".$cat->Category."</br>
                            </p>
                        </div>";
    } */
//echo $strProductHTML;

$url = "https://outline2023.enpr.de/wp-json/wc/v3/products?sku=AB12345"; 
$product_id = wc_get_product_id_by_sku("AB12345");
echo "ID: ".$product_id."<br>";
$strCatsHTML = json_decode(update_woo_product_per_api($url,$product_id),true);//create_woo_product_per_api($url);
echo "ID per API: ".$strCatsHTML[0]["id"];
?>
        <div class="w-full h-screen md:p-0 md:h-auto">
            <div id="intro" class="flex flex-col w-full md:flex-row ">
                <div id="" style="border:2px solid green;" class="relative mx-8 my-4 md:mx-auto lg:mx-8 w-1/4">
                    <?php echo "<pre>".var_export($strCatsHTML,true)."</pre>";//echo "<pre>".var_export($xmlobj,true)."</pre>";?>                    
                </div>
                <div id="" style="border:2px solid tomato;" class="relative mx-8 my-4 md:mx-auto lg:mx-8 w-2/4">
                    <?php echo "!";/* $strProductHTML */;//echo "<pre>".var_export($xmlobj2a,true)."</pre>";?>
                </div>
                <div id="" style="border:2px solid orange;" class="relative mx-8 my-4 md:mx-auto lg:mx-8 w-1/4">
                    <?php echo "!";/* $strCustomerHTML;echo "<pre>".var_export($xmlobj3,true)."</pre>"; */?>
                </div>
            </div>
        </div>

		</main>

		<?php endwhile; // OK, let's stop the page loop once we've displayed it ?>

	<?php endif; // OK, I think that takes care of both scenarios (having a page or not having a page to show) ?>
<script>

  </script>


<?php
get_footer();
?>