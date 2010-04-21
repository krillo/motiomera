//######################################################
//                      Generic                       //
//######################################################
jQuery.fn.bindMetadata = function(options)
{
	// Merge options argument with settings so user can alter defaults.
	var settings = jQuery.extend(
	{
		nameSelector: '.metadata',
		parentClassName: '.metadata-parent',
		findById: true,
		findAuto: true
	}, options);

	return this.each(function()
	{
		// Cache this for performance boost.
		var $bindTothis = $(this);
		var $metadataContainers = [];

		function findMetadata()
		{
			// If we want to look for meta data based on the element's id.
			if (settings.findById && $bindTothis.attr('id'))
			{
				$metadataContainers = $('.metadata.'+$bindTothis.attr('id'));
				if ($metadataContainers.length > 0)
				{
					return true;
				}
			}
			// If we want to traverse the DOM to automaticly find meta data by relation.
			if (settings.findAuto)
			{
				$metadataContainers = $(settings.nameSelector, $bindTothis);
				if ($metadataContainers.length > 0)
				{
					return true;
				}
			}
			// If we don't find any meta data return false.
			if ($metadataContainers.length <= 0)
			{
				return false;
			}
		}

		// If we find metadata, loop through it.
		if (findMetadata())
		{
			$metadataContainers.each(function()
			{
				// Cache current this for performance boost.
				var $metadata = $(this);
				// Make sure we have all the needed arguments.
				if (!$metadata.attr('title') || !$metadata.html().replace(/( |\t)/g,''))
				{
					return false;
				}
				else
				{
					// Bind data to element.
					$bindTothis.data($metadata.attr('title'), $metadata.html());
					// Remove now useless element from the DOM.
					$metadata.remove();
				}
			});	
		}
		// Remove useless class on metadata parents.
		$bindTothis.removeClass(settings.parentClassName);
	});
};
function extendGmaps()
{
	GPolyline.prototype.Distance = GPolygon.prototype.Distance = function()
	{
		var dist = 0;
		for (var i=1; i < this.getVertexCount(); i++)
		{
			dist += this.getVertex(i).distanceFrom(this.getVertex(i-1));
		}
		return dist;
	};
	GPolyline.prototype.GetPointAtDistance = GPolygon.prototype.GetPointAtDistance = function(metres)
	{
		if (metres == 0) 
		{
			return this.getVertex(0);
		}
		if (metres < 0)
		{
			return null;
		}
		var dist=0;
		var olddist=0;
		for (var i=1; (i < this.getVertexCount() && dist < metres); i++)
		{
			olddist = dist;
			dist += this.getVertex(i).distanceFrom(this.getVertex(i-1));
		}
		if (dist < metres)
		{
			return null;
		}
		var p1= this.getVertex(i-2);
		var p2= this.getVertex(i-1);
		var m = (metres-olddist)/(dist-olddist);
		return new GLatLng( p1.lat() + (p2.lat()-p1.lat())*m, p1.lng() + (p2.lng()-p1.lng())*m);

	};
}
function initiatePreload()
{
	var $preload = $.preload = function( original, settings ){
		if( original.split )//selector
			original = $(original);

		settings = $.extend( {}, $preload.defaults, settings );
		var sources = $.map( original, function( source ){
			if( !source ) 
				return;//skip
			if( source.split )//URL Mode
				return settings.base + source + settings.ext;
			var url = source.src || source.href;//save the original source
			if( typeof settings.placeholder == 'string' && source.src )//Placeholder Mode, if it's an image, set it.
				source.src = settings.placeholder;
			if( url && settings.find )//Rollover mode
				url = url.replace( settings.find, settings.replace );
			return url || null;//skip if empty string
		});

		var data = {
			loaded:0,//how many were loaded successfully
			failed:0,//how many urls failed
			next:0,//which one's the next image to load (index)
			done:0,//how many urls were tried
			//found:false,//whether the last one was successful
			total:sources.length//how many images are being preloaded overall
		};

		if( !data.total )//nothing to preload
			return finish();

		var imgs = '<img/>',//ensure one
			thres = settings.threshold;//save a copy

		while( --thres > 0 )//it could be oddly negative
			imgs += '<img/>';
		imgs = $(imgs).load(handler).error(handler).bind('abort',handler).each(fetch);

		function handler( e ){
			data.found = e.type == 'load';
			data.image = this.src;
			var orig = data.original = original[this.index];
			data[data.found?'loaded':'failed']++;
			data.done++;
			if( settings.placeholder && orig.src )//special case when on placeholder mode
				orig.src = data.found ? data.image : settings.notFound || orig.src;
			if( settings.onComplete )
				settings.onComplete( data );
			if( data.done < data.total )//let's continue
				fetch( 0, this );
			else{//we are finished
				if( imgs.unbind )//sometimes IE gets here before finishing line 84
					imgs.unbind('load').unbind('error').unbind('abort');//cleanup
				imgs = null;
				finish();
			}
		};
		function fetch( i, img, retry ){
			if( $.browser.msie && data.next && data.next % $preload.gap == 0 && !retry ){//IE problem, can't preload more than 15
				setTimeout(function(){ fetch( i, img, true ); }, 0);
				return false;
			}
			if( data.next == data.total ) return false;//no more to fetch
			img.index = data.next;//save it, we'll need it.
			img.src = sources[data.next++];
			if( settings.onRequest ){
				data.image = img.src;
				data.original = original[data.next-1];
				settings.onRequest( data );
			}
		};
		function finish(){
			if( settings.onFinish )
				settings.onFinish( data );
		};
	};

	// each time we load this amount and it's IE, we must rest for a while, make it lower if you get stack overflow.
	$preload.gap = 14; 

	$preload.defaults = {
		threshold:4,//how many images to load simultaneously
		base:'',//URL mode: a base url can be specified, it is prepended to all string urls
		ext:'',//URL mode:same as base, but it's appended after the original url.
		replace:''//Rollover mode: replacement (can be left empty)
	};

	$.fn.preload = function( settings ){
		$preload( this, settings );
		return this;
	};
	return $preload;
}

function addSorting()
{
	$('table.sortable:not(.sorted)').addClass('sorted').tablesorter();
}

//######################################################
//                  Fasta utmaningar                  //
//######################################################
var map;
var directions = false;
function fastaUtmaningarGmap()
{
	var $gMapDiv = $('#routeMap #map'); 
	map = new google.maps.Map2($gMapDiv.get(0));
	map.setCenter(new google.maps.LatLng(61.928612,15.79834), 4);
};
function fastaUtmaningar()
{
	if (window.location.search === '?abroad')
	{
		var $abroad = true;
	}
	else
	{
		var $abroad = false;
	}
	function fetchCoordinates(_place)
	{
		if (!$abroad)
		{
			var geocoder = new GClientGeocoder();
			geocoder.getLatLng(
				_place+', Sweden',
				function(point)
				{
					map.setCenter(point);
					return point;
				}
			);
		}
	}
	function plotDirections()
	{
		if (directions)
		{
			directions.clear();
		}
		directions = new GDirections(map);
		var positions = $('select option:selected');
		var locations = [];
		if (positions.length !==0)
		{
			positions.each(function(index)
			{
				var position = $(this).text().indexOf('destination');
				if (position===-1)
				{
					locations[index] = $(this).text()+', Sweden';
				}
			});
			directions.loadFromWaypoints(locations);
		}
	}
	$submitButton = $('input[type="submit"]');
	$submitButton.click(function(event)
	{
		$('select').attr('disabled','');
		$('select:last').attr('disabled','disabled');
		$('form').submit();
		return false;
	});
	var $routeContainer = $('#routeContainer');
	var firstSelection = true;
	$('#regionName').one('focus', function(event)
	{
		$(this).val('');
	});
	var t;
	$('#regionName').bind('keyup', function(event)
	{
		if ($('select', $routeContainer).length === 0 && $(this).val().length > 3)
		{
			clearTimeout(t);
			t = setTimeout(function(){
				addRegionSelection(false);
			}, 500);
		}
		if ($(this).val().length < 4)
		{
			$submitButton.attr('disabled', 'disabled');
		}
		else
		{
			$submitButton.attr('disabled', '');
		}
	});
	function addRegionSelection(_id)
	{
		$('select', $routeContainer).attr('disabled','disabled');
		$submitButton.attr('disabled','');
		var fetchType = '';
		var title;
		if (!_id)
		{
			title = '1';
			fetchType = 'all';
		}
		else
		{
			title = _id;
		}
		$('.removeRoute').addClass('loading').empty();
		function removeButton()
		{
			var $removeButton = $('<span class="removeRoute">x</span>');
			$removeButton.prependTo($('fieldset:last', $routeContainer));
			$removeButton.one('click', function(event)
			{
				var $this = $(this);
				var selectorCount = $('select', $routeContainer).length;
				if (selectorCount > 2)
				{
					$this.parent().prev().find('select').attr('disabled','').parent();
					$this.parent().remove();
					removeButton();
				}
				else if(selectorCount === 2)
				{
					$submitButton.attr('disabled','disabled');
					$this.parent().prev().find('select').attr('disabled','').parent();
					$this.parent().remove();
					$('select', $routeContainer).find('option:first').attr('selected','selected');
				}
				else
				{
					$('select', $routeContainer).find('option:first').attr('selected','selected');
				}
			});
			if (!$abroad)
			{
				plotDirections();
			}
		}
		$('#regionName').attr('disabled','disabled');
		$.ajax(
		{
			url: '/ajax/actions/fastautmaningar.php',
			type: 'POST',
			data: {
				'kommun_id': _id,
				'type': fetchType
			},
			dataType: 'json',
			success: function(_json)
			{
				$('#regionName').attr('disabled','');
				var selectElement = $('<fieldset />');
				$('<legend />').text('Destination').appendTo(selectElement);
				$('<select />').attr('name', 'routes[]').bind('change', function(event)
				{
					$('option:first', this).remove();
					addRegionSelection($(this).val());
				}).appendTo(selectElement);

				$('<option />').text('Välj en destination').appendTo(selectElement.find('select'));
				$.each(_json.routes, function(i,region)
				{
					if (($abroad === true && region.abroad == 'true') ||($abroad === false && region.abroad != 'true'))
					{
						$('<option>'+region.name+'</option>').attr('value', region.id).appendTo(selectElement.find('select'));
					}
				});
				$routeContainer.append(selectElement);
				$('.removeRoute').remove();
				removeButton();
				fetchCoordinates($('select:last', $routeContainer).val());
			},
			error: function()
			{
				$('select', $routeContainer).attr('disabled','');
				$('.removeRoute', $routeContainer).remove();
			}
		});
		if ($('select', $routeContainer).length !== 0 && $('#regionName').val().length > 3)
		{
			$submitButton.attr('disabled', '');
		}
		else
		{
			$submitButton.attr('disabled', 'disabled');
		}
	}
}


//######################################################
//                      Min Sida                      //
//######################################################

function stegGrafikGmaps()
{
	// Bind all the meta data to their respective parents.
	var $gMapDiv = $('#map').bindMetadata();
	// Initiate the google map.
	var map = new google.maps.Map2($gMapDiv.get(0));
	
	extendGmaps();

	// Extend Gmaps GPolygon prototype
	
	var $preload = initiatePreload();

	function fixGmapImageThumbnails()
	{
		$('img.gmnoprint').remove();
		function waitForImagesToLoad()
		{
			var $gMapsIcons = $('#map img[src*="kommunbilder"]');

			if ($gMapsIcons.length === 0)
			{
				setTimeout(waitForImagesToLoad, 100);
			}
			else
			{
				$.preload($gMapsIcons, 
				{
				onFinish: function()
				{
				$gMapsIcons.each(function(index)
				{	
					var $this = $(this).addClass('gmapsImageThumb');
					var initialSrc = $this.attr('src');
					var fullImageSrc = initialSrc.replace(/thumb_/i,'middle_');
					$('<img />').addClass('fetchImgSize').attr('src', fullImageSrc).appendTo('#map-container');

					$this.css('border','3px solid #fff').click(function()
					{
						if($('.gMapsImages').length !== 0)
						{
							$('.gMapsImages').fadeOut(500, function()
							{
								$gMapsIcons.fadeIn(500);
								$(this).remove();
								$this.fadeOut();
								addGMapsImage();
							});
						}
						else
						{
							$this.fadeOut();
							addGMapsImage();
						}
						function addGMapsImage()
						{
							var tempImg = $('img[src='+fullImageSrc+']');
							var initialSrc = $this.attr('src');
							$('<img />').addClass('gMapsImages').attr('src', fullImageSrc).css(
								{
									position: 'absolute',
									border: '3px solid #fff',
									marginTop: (Math.round(tempImg.height()/2)*-1),
									marginLeft: (Math.round(tempImg.width()/2)*-1),
									top: '50%',
									left: '50%',
									cursor: 'pointer'
								}
							).click(function(event)
							{
								$(this).fadeOut(500, function()
								{
									$(this).remove();
									$gMapsIcons.fadeIn(500);
								});
							}).hide().appendTo($('#map')).fadeIn(500);
						}
					});
				});
				}
				});
			}
		}
		waitForImagesToLoad();
	}
	if ($gMapDiv.data('directions-map-type'))
	{
		switch ($gMapDiv.data('directions-map-type'))
		{
			case 'G_NORMAL_MAP':
				map.setMapType(G_NORMAL_MAP);
				break;

			case 'G_SATELLITE_MAP':
				map.setMapType(G_SATELLITE_MAP);
				break;

			case 'G_HYBRID_MAP':
				map.setMapType(G_HYBRID_MAP);
				break;

			default:
				map.setMapType(G_PHYSICAL_MAP);
		}
	}

	map.addControl(new GScaleControl());
	map.addControl(new GSmallMapControl());

	var geocoder = new GClientGeocoder();
	var tempImageContainer;
	if ($gMapDiv.data('directions-avatar'))
	{
		tempImageContainer = $('<img />').attr('src', $gMapDiv.data('directions-avatar'));
		var userAvatar = new GIcon();
		userAvatar.image = $gMapDiv.data('directions-avatar');
		userAvatar.iconAnchor = new GPoint(20,35);
		userAvatar.infoWindowAnchor = new GPoint(66, 0);
	}
	if ($gMapDiv.data('directions-from-image'))
	{
		tempImageContainer = $('<img />').attr('src', $gMapDiv.data('directions-from-image'));
		var directionsFromIcon = new GIcon();
		directionsFromIcon.image = $gMapDiv.data('directions-from-image');
		directionsFromIcon.iconAnchor = new GPoint(20,25);
		directionsFromIcon.infoWindowAnchor = new GPoint(20, 0);
	}
	if ($gMapDiv.data('directions-to-image'))
	{
		tempImageContainer = $('<img />').attr('src', $gMapDiv.data('directions-to-image'));
		var directionsToIcon = new GIcon();
		directionsToIcon.image = $gMapDiv.data('directions-to-image');
		directionsToIcon.iconAnchor = new GPoint(20,25);
		directionsToIcon.infoWindowAnchor = new GPoint(20, 0);
	}
	
	if ($gMapDiv.data('directions-to'))
	{
		// Generate new directions based on the from/to data and link to div we just generated.
		var directions = new GDirections(map);
		directions.loadFromWaypoints([$gMapDiv.data('directions-from'),$gMapDiv.data('directions-to')],{getPolyline:true});
		GEvent.addListener(directions, 'load', function()
		{
			setTimeout(function(){$('#loader').fadeOut(2000);}, 500);
			//var zoomlevel = map.getZoom()-4;
			var poly = directions.getPolyline();
			var eol = Math.round(poly.Distance());
			var currentMarker = new GMarker(poly.getVertex(0), {icon: userAvatar});
			var currentLocation = Math.round(eol*($gMapDiv.data('directions-procent-completed')/100));
			// No idea why we subtract with 1.. Black magic.
			var endLocation = Math.round(eol-1);

			var startPoint = poly.GetPointAtDistance(0);
			var currentPoint = poly.GetPointAtDistance(currentLocation);
			var endPoint = poly.GetPointAtDistance(endLocation);

			map.setCenter(startPoint);

			var fromMarker = new GMarker(startPoint, {icon: directionsFromIcon});
			map.addOverlay(fromMarker);

			var toMarker = new GMarker(endPoint, {icon: directionsToIcon});
			map.addOverlay(toMarker);

			currentMarker.setPoint(startPoint);
			map.addOverlay(currentMarker);
			var currentPosition = 0;
			var incrementInMeters = endLocation/100;
			fixGmapImageThumbnails();
			function moveMarker()
			{
			//	map.setZoom(zoomlevel);
				if (currentPosition > currentLocation)
				{
					// We are finished moving the marker.
					return false;
				}
				currentPosition = Math.round(currentPosition+incrementInMeters);
				var point = poly.GetPointAtDistance(currentPosition);
				currentMarker.setPoint(point);
				setTimeout(moveMarker, 50);
			}
			setTimeout(moveMarker, 2500);
		});
	}
	else if($gMapDiv.data('directions-from'))
	{
		geocoder.getLatLng(
			$gMapDiv.data('directions-from'),
			function(point)
			{
				if (point)
				{
					setTimeout(function(){$('#loader').fadeOut(2000);}, 500);
					var currentMarker = new GMarker(point, {icon: userAvatar});
					map.setCenter(point, 9);

					map.addOverlay(currentMarker);
				}
			}
		);

	}
}
function stegGrafik()
{
	// ?
}
function editKommun()
{
	$whatWeDontNeed = $('#areacode, #county, #sameAsArea');
	$('.abroad').change(function()
	{
		if ($(this).is(':checked'))
		{
			$whatWeDontNeed.hide().find('input, select').attr('disabled', 'disabled');
			$('#area th:first').text('Land');
		}
		else
		{
			$whatWeDontNeed.show().find('input, select').attr('disabled', '');
			$('#area th:first').text('Ort');
		}
	});
}
function paminnelseSQL()
{
	var epostIsSet = false;
	var epostFound = false;
	$('textarea[name="query"]').keyup(function(){
		var $sql = $(this).val().replace(/\n/ig, '').replace(/select/i, '').split(/FROM/i)[0].split(',');
		var matches = [];
		var doubleCheck = [];
		$(doubleCheck).empty();
		var thismatch;
		$('#doubles').empty();
		$($sql).each(function(index)
		{
			thismatch = this.replace(/^\s+/,'').replace(/\s+$/,'').replace(/[^]+ as /i,'').replace(/[^]+\./i,'');
			if (doubleCheck[thismatch]) {
				$('#doubles').append(thismatch + ' finns flera gånger!<br />');
			} else {
				if (!thismatch.length == 0 && !thismatch.match(/(\(|\))/g)){
					doubleCheck[thismatch] = true;
					matches[matches.length] = thismatch;
				}
			}
		});
		
		var $shortcuts = $('#shortcuts ul').empty();
		if ($(this).val().length) {
			$(matches).each(function(){
				$('#shortcuts ul').append($('<li>' + this + '</li>').css('cursor','pointer').click(insertWord));
			});
		} else {
			$('#shortcuts ul').append('</ul>Det finns inga genvägar!<ul>');
		}
		if (!$('#doubles').text().length) {
			$('#doubles').append('<span style="color:#0A0">Inga dubletter hittades</span>');
		}
		if (!$('textarea[name="query"]').val().match(/id in \(#medlemslista#\)/gi)) {
			$('#medlemslistaSet').hide();
			$('#medlemslistaUnset').show();
		} else {
			$('#medlemslistaUnset').hide();
			$('#medlemslistaSet').show();
		}
	}).keyup();

	function insertWord () {
		var temp = $('#inre_mall').val();
		$('#inre_mall').val(temp + '#' + $(this).text() + '#').focus();
	};
}

function minaQuizSkapa()
{
	var count = 0;
	var $questionFormTemplate = $('#fraga_0').clone();
	$('.addQuestion').click(addQuestion).css({'cursor': 'pointer'});

	function addQuestion ()
	{
		count ++;
		var $thisform = $questionFormTemplate.clone();

		$thisform
			.find('table')
			.attr('id', 'fraga_'+count)
			.end()
		.find('tr:first td:first')
			.prepend($('<a />').text('Ta bort denna fråga')
			.addClass('removeQuestion')
			.css({
				'margin-right': 10,
				'float': 'right',
				'cursor': 'pointer'}
			))
			.find('a')
			.click(removeQuestion);

		$('#fragor').append($thisform);
	}
	function removeQuestion()
	{
		$(this).parent().parent().parent().remove();
	}
	var $privacyOptions = $('#privacyOptions').hide();
	$('input[value="vissa"]').filter('[type="radio"]').click(function()
	{
		$privacyOptions.show();
	});
	$('input[value="alla"]').filter('[type="radio"]').click(function()
	{
		$privacyOptions.hide();
	});
}
function minaQuizAndra()
{
	var count = $('.question').length+1;
	var $formTemplate = $('#fraga_0').show().clone(true);
	$('#fraga_0').remove();
	$('.addQuestion').click(addQuestion).css({'cursor': 'pointer'});

	function addQuestion ()
	{
		var $thisform = $formTemplate.clone();
		var $inputs =  $thisform.find(':input');
		$inputs.each(function(index)
		{
			var $newName = $(this).attr('name').replace(/\[new_[0-9]+/,'[new_'+count);
			$(this).attr('name', $newName);
		});
		$lastNr =  parseInt($('#fragor table:last tr:first td:first').text().replace(/[^0-9]/g,''))+1;
		$('#fragor').append($thisform.find('tr:first td:first').text('Fråga #'+$lastNr+':').end());
		count++;
	}
	
	var $privacyOptions = $('#privacyOptions').hide();
	$('input[value="vissa"]').filter('[type="radio"]').click(function()
	{
		$privacyOptions.show();
	});
	$('input[value="alla"]').filter('[type="radio"]').click(function()
	{
		$privacyOptions.hide();
	});
}
function minaQuiz()
{

	$('.hide_hidden_questions, .show_all_questions').css({'cursor': 'pointer'}).click(toogle);
	
	function toogle()
	{
		if (!$('.hidden_questions').is(':visible'))
		{
			$(this).fadeOut(500);
			$('.hidden_questions').fadeIn(500);
		}
		else
		{
			$('.show_all_questions').fadeIn(500);
			$('.hidden_questions').fadeOut(500);
		}
	}
}
function minaQuizSvara()
{
	var $referal = $('var#referal').text();
	var $quizContainer = $('#quizContainer');
	var $quizData = [];
	var $quizAnswers = [];
	var $currentQuiz = 0;
	var $quizSuccessful = 0;
	var $countdownTimer;
	var $countdownTimeout = 60;
	var $countdownContainer = $('<div id="countdown"><h3>Du har <strong>'+$countdownTimeout+'</strong> sekunder kvar</h3></div>');
	$.ajax(
	{
		url:"/actions/minaquiz.php",
		type:"POST",
		dataType: 'json',
		data:{'id':$('var#quizId').text()},
		success: function(json)
		{
			$quizData = json.questions;
			addQuestion();
		},
		error: function()
		{
			alert("Det verkar inte finnas några frågor kvar för dig att svara på i detta Quiz. \n\nKlicka på ok för att gå tillbaka.");
			window.location = $referal;
		}
	});

	function addQuestion()
	{
		if (true)
		{
			var $quizContent;
			var $answerButton = $('<img />').attr('src', '/img/icons/quiz_ratta.gif').attr('id','answerButton').click(answerQuestion);
			var $titleContainer = $('<div />').addClass('mmh1 mmMarginBottom').text($quizData[$currentQuiz]['title']);
			var $quizStatusContainer = $('<div />').addClass('mmFontBold12').text('Fråga '+($currentQuiz+1)+' av '+$quizData.length);
			var $quizQuestionContainer = $('<div />').attr('id','text_fraga').text($quizData[$currentQuiz]['question']);
			var $quizAnswersContainer = $('<div />').attr('id','radio_div');
			var $checkBox = $('<img />').attr('src', '/img/icons/quiz_svarsbox.gif').addClass('mmMarginRight10 checkBox').toggle(function()
				{
					if ($('#answerButton').length == 0)
					{
						$quizContainer.append($answerButton.clone(true));
					}
					$('img.checkBox').attr('src', '/img/icons/quiz_svarsbox.gif').data('selected', '');

					var $this = $(this);
					$this.attr('src', '/img/icons/quiz_svarsbox_check.gif');
					$this.data('selected', 'selected');
				},
				function()
				{
					var $this = $(this);
					$this.attr('src', '/img/icons/quiz_svarsbox.gif');
					$this.data('selected', '');
				});
			var $correctAnswer1 = $quizData[$currentQuiz]['fraga_1'];
			var $wrongAnswer1 = $quizData[$currentQuiz]['fraga_2'];
			var $wrongAnswer2 = $quizData[$currentQuiz]['fraga_3'];

			$quizContainer.empty().append($quizContent);
			$quizContainer.append($quizStatusContainer).append('<br />').append($quizQuestionContainer).append('<br /><br />');
			$($quizData[$currentQuiz].answers).each(function(index)
			{
				var $isCorrect;
				if (this.isCorrect === 'true' )
				{
					$isCorrect = 'true';
				}
				else
				{
					$isCorrect = 'false';
				}

				$quizAnswersContainer.append($checkBox.clone(true).data('isCorrect', $isCorrect).css({float:'left'})).append($('<div />').addClass('mmQuizAlternativeText').text(this.answer)).append('<div style="clear: left;"/><br />');
			});
			$quizContainer.append($quizAnswersContainer).append($countdownContainer.clone());
			$countdownTimer = setTimeout(countdown, 1000);
		}
		else
		{
			finishedQuiz();
		}
	}
	function answerQuestion()
	{
		clearTimeout($countdownTimer);
		$value = false;
		$('img.checkBox').each(function(index)
		{
			if ($(this).data('selected') === 'selected' && $(this).data('isCorrect') === 'true')
			{
				$value = 'true';
				$quizSuccessful++;
			}
		});
		$.post('/actions/minaquiz.php',
			{
				answer: $value,
				id: $quizData[$currentQuiz]['question_id']
			},
			function()
			{
				$isLastQuestion = false;
				
				if (($currentQuiz+1) == $quizData.length)
				{
					$isLastQuestion = true;
				}
				showUserResults($value, $isLastQuestion);
			}
		);
	}
	function showUserResults($correct, _last)
	{
		var $timeIsUpContainer = $('<div> /').addClass('time_is_up');
		var $nextQuestion = $('<div />').addClass('mmQuizNasta').text('Nästa fråga » ');
		var $finalQuestion = $('<span />').html('Du är nu färdig med detta quizet.<br/><br /><div class="mmQuizAnswer mmGreen">RESULTAT</div><h3>Du klarade '+$quizSuccessful+' av '+$quizData.length+' frågor.</h3><br /><a href="'+$referal+'"><img class="mmMarginRight10" src="/img/icons/ArrowCircleGreen.gif"/>Tillbaka</a>');
		
		var $isCorrect = $('<span />').attr('id', 'ratt_eller_fel');
		
		if ($correct === 'true')
		{
			$isCorrect.text('Rätt! ');
			var $quizAnswer = $('<div />').addClass('mmQuizAnswer');
		}
		else
		{
			$isCorrect.text('Fel! ');
			var $quizAnswer = $('<div />').addClass('mmQuizAnswerFel');
		}

		$quizAnswer.append($isCorrect);
		$($quizData[$currentQuiz].answers).each(function(index)
		{
			if (this.isCorrect === 'true' )
			{
				$quizAnswer.append('Det korrekta svaret var "'+this.answer+'"');
			}
		});

		$timeIsUpContainer.append($quizAnswer);

		if (!_last)
		{
			$timeIsUpContainer.append('<br />').append($nextQuestion);
		}
		else
		{
			$timeIsUpContainer.append('<br />').append($finalQuestion);
		}
		$quizContainer.empty().append($timeIsUpContainer);
		$('.mmQuizNasta').one('click', function(event)
		{
			$currentQuiz++;
			addQuestion();
		});
	}
	function countdown()
	{
		var $countDownContainer = $('#countdown');
		var $currentTime = parseInt($('#countdown strong').text());

		if ($countdownContainer.is(":not(:visible)"))
		{
			$countDownContainer.show().find('strong').text($countdownTimeout);
			$countdownTimer = setTimeout(countdown, 1000);
		}
		else if ($currentTime <= 0)
		{
			clearTimeout($countdownTimer);
			$countDownContainer.hide();
			answerQuestion();
			
			$timeIsUp = $('<div />').text('Tiden är ute!');
			$seeCorrectAnswer = $('<div />').addClass('js_close').text('Se rätt svar');
		}
		else
		{
			$('strong', $countDownContainer).text($currentTime-1);
			$countdownTimer = setTimeout(countdown, 1000);
		}
	}
	/*function finishedQuiz()
	{
		
	}*/
}

function stegAddVerification() {
	setTimeout(function()
		{
			$('#motiomera_steg_spara').click(function(event) {

		antalRader = rapporteradeSteg.length;
		aid = Array();
		datum = Array();
		antalSteg = Array();

		var i = 0;
		var url = 'typ=stegtotal&antalsteg=' + antalRader;
		for(;i < antalRader; i++) {
			var month = (rapporteradeSteg[i]["datum"].getMonth()+1 < 10) ? "0" + (rapporteradeSteg[i]["datum"].getMonth() + 1) : rapporteradeSteg[i]["datum"].getMonth()+1;
			var date = (rapporteradeSteg[i]["datum"].getDate() < 10) ? "0" + rapporteradeSteg[i]["datum"].getDate() : rapporteradeSteg[i]["datum"].getDate();
			var datumStr = rapporteradeSteg[i]["datum"].getFullYear() + "-" + month + "-" + date;

			aid[i] = rapporteradeSteg[i]["aid"];
			datum[i] = datumStr;
			antalSteg[i] = rapporteradeSteg[i]["antal"];

			url = url + "&steg" + i + "_aid=" + rapporteradeSteg[i]["aid"];
			url = url + "&steg" + i + "_datum=" + datumStr;
			url = url + "&steg" + i + "_antal=" + rapporteradeSteg[i]["antal"];
		}

		$.ajax({
			url: '/ajax/actions/validate.php',
			type: 'POST',
			dataType: 'HTML',
			data: url,
			success: function(html) {
				if(html=='ok') {
					window.location ='/pages/minsida.php';
				}
				else if (html=='ok_f') {
					alert('Du har klarat en fast utmaning och då fått en pokal!');
					window.location = '/pages/minsida.php';
				}
				else if(html=='ok_nykommun') {
					window.location = '/pages/nykommun.php';
				}
				else {
					alert('Det gick ej att lägga till stegen. ' + html);
				}

			},
			error: function(html) {

			}
		});
	});

		},500);
}

function fixSelect()
{
	$('#aktivitetLista select:first').change(function()
	{
		$('.extraSelect').remove();
		var $this = $(this);
		$this.attr('id','steg_aid').attr('name','steg_aid');
		$selectedObject = $('option:selected', this);


		var json = motiomera_steg_getGrades($selectedObject.text().replace(/ \(min.*/i,''));
		var count = 0;
		var $extraSelect = $('<select />').addClass('extraSelect').attr('name', 'steg_aid').attr('id', 'steg_aid');
		$.each(json, function(i,object)
		{
			if (object.grade)
			{
				var $option = $('<option />').val(object.id).text(object.grade);
				$extraSelect.append($option);
				count++;
			}
		});
		if (count>1)
		{
			$('#steg_aid').attr('id','').attr('name','');
			$this.parent().after($extraSelect);
		}
	});
}

function activitySelects()
{
	//setTimeout(fixSelect,500);
	fixSelect();
}