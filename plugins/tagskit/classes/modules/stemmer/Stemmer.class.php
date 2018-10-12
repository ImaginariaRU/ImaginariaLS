<?php

/**
 * Модуль для выделения основной части слова (stemming)
 * Код модуля взят у Дмитрия Котерова
 */
class PluginTagskit_ModuleStemmer extends Module {

	protected $VERSION = "0.02";
	protected $Stem_Caching = 0;
	protected $Stem_Cache = array();
	protected $VOWEL = '/аеиоуыэюя/u';
	protected $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/u';
	protected $REFLEXIVE = '/(с[яь])$/u';
	protected $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|еых|ую|юю|ая|яя|ою|ею)$/u';
	protected $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/u';
	protected $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/u';
	protected $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|и|ы|ь|ию|ью|ю|ия|ья|я)$/u';
	protected $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/u';
	protected $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/u';

	public function Init() {

	}

	protected function s(&$s, $re, $to) {
		$orig = $s;
		$s = preg_replace($re, $to, $s);
		return $orig !== $s;
	}

	protected function m($s, $re) {
		return preg_match($re, $s);
	}

	/**
	 * Возвращает основу слова
	 *
	 * @param string $word
	 *
	 * @return string
	 */
	public function Stem($word) {
		$word = mb_strtolower($word,'UTF-8');
		$word = strtr($word, array('ё' => 'е'));
		# Check against cache of stemmed words
		if ($this->Stem_Caching && isset($this->Stem_Cache[$word])) {
			return $this->Stem_Cache[$word];
		}
		$stem = $word;
		do {
			if (!preg_match($this->RVRE, $word, $p)) break;
			$start = $p[1];
			$RV = $p[2];
			if (!$RV) break;

			# Step 1
			if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
				$this->s($RV, $this->REFLEXIVE, '');

				if ($this->s($RV, $this->ADJECTIVE, '')) {
					$this->s($RV, $this->PARTICIPLE, '');
				} else {
					if (!$this->s($RV, $this->VERB, ''))
						$this->s($RV, $this->NOUN, '');
				}
			}

			# Step 2
			$this->s($RV, '/и$/u', '');

			# Step 3
			if ($this->m($RV, $this->DERIVATIONAL))
				$this->s($RV, '/ость?$/u', '');

			# Step 4
			if (!$this->s($RV, '/ь$/u', '')) {
				$this->s($RV, '/ейше?/u', '');
				$this->s($RV, '/нн$/u', 'н');
			}

			$stem = $start.$RV;
		} while(false);
		if ($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
		return $stem;
	}

	protected function stem_caching($parm_ref) {
		$caching_level = @$parm_ref['-level'];
		if ($caching_level) {
			if (!$this->m($caching_level, '/^[012]$/')) {
				die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
			}
			$this->Stem_Caching = $caching_level;
		}
		return $this->Stem_Caching;
	}

	protected function clear_stem_cache() {
		$this->Stem_Cache = array();
	}



}