<?php
	$URL= "http://query.yahooapis.com/v1/public/yql?q=Select%20Name%2C%20Symbol%2C%20LastTradePriceOnly%2C%20Change%2C%20ChangeinPercent%2C%20PreviousClose%2C%20DaysLow%2C%20DaysHigh%2C%20Open%2C%20YearLow%2C%20YearHigh%2C%20Bid%2C%20Ask%2C%20AverageDailyVolume%2C%20OneyrTargetPrice%2C%20MarketCapitalization%2C%20Volume%2C%20Open%2C%20YearLow%20from%20yahoo.finance.quotes%20where%20symbol%3D%22" . $_GET["symbol"] . "%22&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";
	$URL2 = "http://feeds.finance.yahoo.com/rss/2.0/headline?s=" . $_GET["symbol"] . "&region=US&lang=en-US";
	$xml_stock = simplexml_load_file($URL);
	$xml_news = simplexml_load_file($URL2);

	if($xml_stock->results->quote->Change[0] != "")
	{
		$Bid = (float)$xml_stock->results->quote->Bid[0];
		$Bid = number_format($Bid,2);
		$Change = (float)$xml_stock->results->quote->Change[0];
		$Change = number_format($Change,2);
		$DaysLow = (float)$xml_stock->results->quote->DaysLow[0];
		$DaysLow = number_format($DaysLow,2);
		$DaysHigh = (float)$xml_stock->results->quote->DaysHigh[0];
		$DaysHigh = number_format($DaysHigh,2);
		$YearLow = (float)$xml_stock->results->quote->YearLow[0];
		$YearLow = number_format($YearLow,2);
		$YearHigh = (float)$xml_stock->results->quote->YearHigh[0];
		$YearHigh = number_format($YearHigh,2);
		$MarketCapitalization = $xml_stock->results->quote->MarketCapitalization[0];
		$Name = $xml_stock->results->quote->Name[0];
		$Open = (float)$xml_stock->results->quote->Open[0];
		$Open = number_format($Open,2);
		$PreviousClose = (float)$xml_stock->results->quote->PreviousClose[0];
		$PreviousClose = number_format($PreviousClose,2);
		$ChangeinPercent = (float)$xml_stock->results->quote->ChangeinPercent[0];
		$ChangeinPercent = number_format($ChangeinPercent,2);
		$symbol = $xml_stock->results->quote->Symbol[0];
		$LastTradePriceOnly = (float)$xml_stock->results->quote->LastTradePriceOnly[0];
		$LastTradePriceOnly = number_format($LastTradePriceOnly,2);
		$OneyrTargetPrice = (float)$xml_stock->results->quote->OneyrTargetPrice[0];
		$OneyrTargetPrice = number_format($OneyrTargetPrice,2);
		$Volume = (float)$xml_stock->results->quote->Volume[0];
		$Volume = number_format($Volume);
		$Ask = (float)$xml_stock->results->quote->Ask[0];
		$Ask = number_format($Ask,2);
		$AverageDailyVolume = (float)$xml_stock->results->quote->AverageDailyVolume[0];
		$AverageDailyVolume = number_format($AverageDailyVolume);
	}
	if($Change == "")
	{
		$ChangeType = '';
	}
	else if($Change >= 0) 
	{
		$ChangeType = '+';
		
	}
	else
	{
		$ChangeType = '-';
		$Change *= -1;
		$ChangeinPercent *= -1;
	}
	$ChangeinPercent .= '%';

	$xml_result = new SimpleXMLElement('<result/>');
	$xml_result->addchild('Name',$Name);
	$xml_result->addchild('Symbol',$symbol);
	$quote = $xml_result->addchild('Quote');
	$quote->addchild('ChangeType',$ChangeType);
	$quote->addchild('Change',$Change);
	$quote->addchild('ChangeinPercent',$ChangeinPercent);
	$quote->addchild('LastTradePriceOnly',$LastTradePriceOnly);
	$quote->addchild('PreviousClose',$PreviousClose);
	$quote->addchild('DaysLow',$DaysLow);
	$quote->addchild('DaysHigh',$DaysHigh);
	$quote->addchild('Open',$Open);
	$quote->addchild('YearLow',$YearLow);
	$quote->addchild('YearHigh',$YearHigh);
	$quote->addchild('Bid',$Bid);
	$quote->addchild('Volume',$Volume);
	$quote->addchild('Ask',$Ask);
	$quote->addchild('AverageDailyVolume',$AverageDailyVolume);
	$quote->addchild('OneyearTargetPrice',$OneyrTargetPrice);
	$quote->addchild('MarketCapitalization',$MarketCapitalization);

	$news = $xml_result->addchild('News');
	for($i=0;$i<count($xml_news->channel->item);$i++)
	{
		$title = $xml_news->channel->item[$i]->title[0];
		$title = str_replace("’","&#039;",$title);
		$title = str_replace("‘","&#039;",$title);
		$link = $xml_news->channel->item[$i]->link[0];
		$item = $news->addchild('Item');
		$item->Title = $title;
		$item->Link = $link;
	}
	$StockChartImageURL = "http://chart.finance.yahoo.com/t?s=" . $symbol . "&lang=enUS&amp;width=%20300&height=180";
	$xml_result->StockChartImageURL = $StockChartImageURL;
	Header('Content-type: text/xml');
	echo $xml_result->asXML();
	?>