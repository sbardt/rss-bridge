<?php
class TheCodingLoveBridge extends BridgeAbstract{

	public function loadMetadatas() {

		$this->maintainer = "superbaillot.net";
		$this->name = "The Coding Love";
		$this->uri = "http://thecodinglove.com/";
		$this->description = "The Coding Love";
		$this->update = "04/02/2015";

	}

    public function collectData(array $param){
        $html = $this->file_get_html('http://thecodinglove.com/') or $this->returnError('Could not request The Coding Love.', 404);
    
        foreach($html->find('div.post') as $element) {
            $item = new Item();
            $temp = $element->find('h3 a', 0);
            
            $titre = $temp->innertext;
            $url = $temp->href;
            
            $temp = $element->find('div.bodytype', 0);
            
            // retrieve .gif instead of static .jpg
            $images = $temp->find('p.e img');
            foreach($images as $image){
              $img_src = str_replace(".jpg",".gif",$image->src);
              $image->src = $img_src;
            }
            $content = $temp->innertext;

            $auteur = $temp->find('i', 0);
            $pos = strpos($auteur->innertext, "by");
            
            if($pos > 0)
            {
                $auteur = trim(str_replace("*/", "", substr($auteur->innertext, ($pos + 2))));
                $item->name = $auteur;
            }
            
            
            $item->content .= trim($content);
            $item->uri = $url;
            $item->title = trim($titre);
            
            $this->items[] = $item;
        }
    }

    public function getName(){
        return 'The Coding Love';
    }

    public function getURI(){
        return 'http://thecodinglove.com/';
    }

    public function getCacheDuration(){
        return 7200; // 2h hours
    }
    public function getDescription(){
        return "The Coding Love via rss-bridge";
    }
}
?>
