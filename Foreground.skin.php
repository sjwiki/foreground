<?php

/**
 * Skin file for Foreground
 *
 * @file
 * @ingroup Skins
 */
 

class Skinforeground extends SkinTemplate {
	public $skinname = 'foreground', $stylename = 'foreground', $template = 'foregroundTemplate', $useHeadElement = true;

	public function setupSkinUserCss(OutputPage $out) {
		parent::setupSkinUserCss($out);
		global $wgForegroundFeatures;
		$wgForegroundFeaturesDefaults = array(
			'showActionsForAnon' => true,
			'NavWrapperType' => 'divonly',
			'showHelpUnderTools' => true,
			'showRecentChangesUnderTools' => true,
			'IeEdgeCode' => 1,
			'addThisFollowPUBID' => ''

		);
		foreach ($wgForegroundFeaturesDefaults as $fgOption => $fgOptionValue) {
			if ( !isset($wgForegroundFeatures[$fgOption]) ) {
				$wgForegroundFeatures[$fgOption] = $fgOptionValue;
			}
		}
		switch ($wgForegroundFeatures['IeEdgeCode']) {
			case 1:
				$out->addHeadItem('ie-meta', '<meta http-equiv="X-UA-Compatible" content="IE=edge" />');
				break;
			case 2:
				if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
					header('X-UA-Compatible: IE=edge');
				break;
		}
		$out->addModuleStyles('skins.foreground');
	}

	public function initPage( OutputPage $out ) {
		global $wgLocalStylePath;
		parent::initPage($out);

		$viewport_meta = 'width=device-width, user-scalable=yes, initial-scale=1.0';
	  $out->addMeta('viewport', $viewport_meta);
		$out->addModuleScripts('skins.foreground');
	}

}
?>

<div id="container"> <!-- whole page wrapper -->

<?php
class foregroundTemplate extends BaseTemplate {
	public function execute() {
		global $wgUser;
		global $wgForegroundFeatures;
		wfSuppressWarnings();
		$this->html('headelement');
		switch ($wgForegroundFeatures['NavWrapperType']) {
			case '0':
				break;
			case 'divonly':
				echo "<div id='navwrapper'>";
				break;
			default:
				echo "<div id='navwrapper' class='". $wgForegroundFeatures['NavWrapperType']. "'>";
				break;
		}
?>
<!-- START FOREGROUNDTEMPLATE -->
		<nav class="top-bar">
						<ul class="title-area">
							<li class="name"><h1><a href="<?php echo $this->data['nav_urls']['mainpage']['href']; ?>"><?php echo $this->text('sitename'); ?></a></h1></li>
						   <li class="toggle-topbar menu-icon"><a href="#"><span><?php echo wfMessage( 'foreground-menutitle' )->text(); ?></span></a></li>
						</ul>

						<section class="top-bar-section">

		    		<ul id="top-bar-left" class="left">
		 						<li class="divider"></li>
									<?php foreach ( $this->getSidebar() as $boxName => $box ) { if ( ($box['header'] != wfMessage( 'toolbox' )->text())  ) { ?>
									<li class="has-dropdown active"  id='<?php echo Sanitizer::escapeId( $box['id'] ) ?>'<?php echo Linker::tooltip( $box['id'] ) ?>>
											<a href="#"><?php echo htmlspecialchars( $box['header'] ); ?></a>
											<?php if ( is_array( $box['content'] ) ) { ?>
												<ul class="dropdown">
													<?php foreach ( $box['content'] as $key => $item ) { echo $this->makeListItem( $key, $item ); } ?>
        								</ul>
											<?php } } ?>
									<?php } ?>
		    		</ul>

		        <ul id="top-bar-right" class="right">
			      <!--<li class="has-form">
		        	<form action="<?php $this->text( 'wgScript' ); ?>" id="searchform" class="mw-search">
		        		<div class="row collapse">
		            	<div class="small-8 columns">
		        				<?php echo $this->makeSearchInput(array('placeholder' => wfMessage('searchsuggest-search')->text(), 'id' => 'searchInput') ); ?>
		        			</div>
		        			 <div class="small-4 columns">
		        				<button type="submit" class="button search"><?php echo wfMessage( 'search' )->text() ?></button>
		        			</div>
		        		</div>
		        	</form>
		        </li>-->
				<li class="has-form">
					<form action="<?php $this->text( 'wgScript' ); ?>" id="searchform" class="mw-search">
						<div class="row">
						<div class="small-12 columns">
							<?php echo $this->makeSearchInput(array('placeholder' => wfMessage('searchsuggest-search')->text(), 'id' => 'searchInput') ); ?>
							<button type="submit" class="button search"><?php echo wfMessage( 'search' )->text() ?></button>
						</div>
						</form>
				</li>
		         <li class="divider show-for-small"></li>
		         <li class="has-form">

								<li class="has-dropdown active"><a href="#"><i class="fa fa-cogs"></i></a>
									<ul id="toolbox-dropdown" class="dropdown">
										<?php foreach ( $this->getToolbox() as $key => $item ) { echo $this->makeListItem($key, $item); } ?>
										<?php if ($wgForegroundFeatures['showRecentChangesUnderTools']): ?><li id="n-recentchanges"><?php echo Linker::specialLink('Recentchanges') ?></li><?php endif; ?>
										<?php if ($wgForegroundFeatures['showHelpUnderTools']): ?><li id="n-help" <?php echo Linker::tooltip('help') ?>><a href="/wiki/Help:Contents"><?php echo wfMessage( 'help' )->text() ?></a></li><?php endif; ?>
									</ul>
								</li>

							<?php if ($wgUser->isLoggedIn()): ?>
								<li id="personal-tools-dropdown" class="has-dropdown active"><a href="#"><i class="fa fa-user"></i></a>
									<ul class="dropdown">
									<?php foreach ( $this->getPersonalTools() as $key => $item ) { echo $this->makeListItem($key, $item); } ?>
									</ul>
								</li>

							<?php else: ?>
							<li>
								<?php if (isset($this->data['personal_urls']['anonlogin'])): ?>
									<a href="<?php echo $this->data['personal_urls']['anonlogin']['href']; ?>"><?php echo wfMessage( 'login' )->text() ?></a>
								<?php elseif (isset($this->data['personal_urls']['login'])): ?>
									<a href="<?php echo htmlspecialchars($this->data['personal_urls']['login']['href']); ?>"><?php echo wfMessage( 'login' )->text() ?></a>
								<?php else: ?>
									<?php echo Linker::link(Title::newFromText('Special:UserLogin'), wfMessage( 'login' )->text()); ?>
								<?php endif; ?>
							</li>

							<?php endif; ?>

		       </ul>
		     </section>
		</nav>
		<?php if ($wgForegroundFeatures['NavWrapperType'] != '0') echo "</div>"; ?>
		
		<div id="page-content"> <!-- developers added this but SJWiki's footer forced to bottom needs something else, will leave in but completely ignore-->
		<div id="body"> <!-- this is for our use, we need to exclude footer -->

		<div class="row">
				<div class="large-12 columns">
				<!--[if lt IE 9]>
				<div id="siteNotice" class="sitenotice panel radius"><?php echo $this->text('sitename') . ' '. wfMessage( 'foreground-browsermsg' )->text(); ?></div>
				<![endif]-->

				<?php if ( $this->data['sitenotice'] ) { ?><div id="siteNotice" class="sitenotice panel radius"><?php $this->html( 'sitenotice' ); ?></div><?php } ?>
				<?php if ( $this->data['newtalk'] ) { ?><div id="usermessage" class="newtalk panel radius"><?php $this->html( 'newtalk' ); ?></div><?php } ?>
				</div>
		</div>

		<div id="mw-js-message" style="display:none;"></div>

		<div class="row">
				<div id="p-cactions" class="large-12 columns">
					<?php if ($wgUser->isLoggedIn() || $wgForegroundFeatures['showActionsForAnon']): ?>
						<a href="#" data-dropdown="drop1" class="button dropdown small secondary radius"><i class="fa fa-cog"><span class="show-for-medium-up">&nbsp;<?php echo wfMessage( 'actions' )->text() ?></span></i></a>
						<ul id="drop1" class="views large-12 columns f-dropdown">
							<?php foreach( $this->data['content_actions'] as $key => $item ) { echo preg_replace(array('/\sprimary="1"/','/\scontext="[a-z]+"/','/\srel="archives"/'),'',$this->makeListItem($key, $item)); } ?>
							<?php wfRunHooks( SkinTemplateToolboxEnd, array( &$this, true ) );  ?>
						</ul>
						<?php if ($wgUser->isLoggedIn()): ?>
							<div id="echo-notifications"></div>
						<?php endif; ?>
					<?php endif;
					$namespace = str_replace('_', ' ', $this->getSkin()->getTitle()->getNsText());
					$displaytitle = $this->data['title'];
					if (!empty($namespace)) {
						$pagetitle = $this->getSkin()->getTitle();
						$newtitle = str_replace($namespace.':', '', $pagetitle);
						$displaytitle = str_replace($pagetitle, $newtitle, $displaytitle);
					?><h4 class="namespace label"><?php print $namespace; ?></h4><?php } ?>
					<h2 class="title"><?php print $displaytitle; ?></h2>
					<!-- <?php if ( $this->data['isarticle'] ) { ?><h3 id="tagline"><?php $this->msg( 'tagline' ) ?></h3><?php } ?> -->
					<h5 class="subtitle"><?php $this->html('subtitle') ?></h5>
					<div class="clear_both"></div>
					<?php $this->html('bodytext') ?>
		    	<div class="group"><?php $this->html('catlinks'); ?></div>
		    	<?php $this->html('dataAfterContent'); ?>
		    </div>
		</div>
		</div>
		<div id="footer">
		<footer class="row">

		<?php if ($wgForegroundFeatures['addThisFollowPUBID'] != '') { ?>
				<div class="social-footer large-12 small-12 columns">
					<div class="social-links">
					<!-- Go to www.addthis.com/dashboard to customize your tools -->
					<div class="addthis_horizontal_follow_toolbox"></div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $wgForegroundFeatures['addThisFollowPUBID'];?>"></script>
					</div>
				</div>
		<?php } ?>

		<ul class="large-12 columns">
		<?php foreach ( $this->getFooterLinks( "flat" ) as $key ) { ?>
			<li id="footer-<?php echo $key ?>"><?php $this->html( $key ) ?></li>
		<?php } ?>
<!--		</ul>
	<ul>
                <ul class="large-12 columns">
-->
                <?php foreach ( $this->getFooterIcons( "nocopyright" ) as $blockName => $footerIcons ) { ?>
	         <li id="<?php echo $blockName ?>"><?php foreach ( $footerIcons as $icon ) { ?>
	         <?php echo $this->getSkin()->makeFooterIcon( $icon, 'withoutImage' ); ?><?php } ?></li>
				<?php } ?>
		</ul>
		</footer>
		</div>

		</div>
</div> <!-- end of whole page wrpapper -->		
		<?php $this->printTrail(); ?>

		</body>
		</html>

<?php
		wfRestoreWarnings();
	}
}
?>
