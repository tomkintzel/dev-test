<?php
/**
 * @var array $model
 * @var bool $withText
 */

/** @var MSQ_Slider $slider */
$slider = $model['slider'];
$withText = $model['with-text'];

$slider->setClasses(['ReferenceSlider']);
$slider->setEntryFormatter(function($entry) use ($withText) {
    $classes = '';

    if (!$withText) {
        $classes .= ' ReferenceSlider-Slide-noBorder ReferenceCard-noText';
    }

   ob_start(); ?>
    <div class="ReferenceSlider-Slide ReferenceCard<?php echo $classes; ?>">
        <div class="ReferenceCard-ImageViewPort">
            <img src="<?php echo $entry['image']; ?>" class="ReferenceCard-Image" />
        </div>
        <?php if ($withText === true) : ?>
            <div class="ReferenceCard-Title">
                <?php echo $entry['title']; ?>
            </div>
            <p class="ReferenceCard-Text">
                <?php echo $entry['text']; ?>
            </p>
        <?php endif; ?>
    </div>
    <?php
    $formattedEntry = ob_get_clean();

    return $formattedEntry;
});

$classes = '';

if (!$withText) {
    $classes .= ' ReferenceEmbed-noText';
}

?>
<div class="ReferenceEmbed<?php echo $classes; ?>">
    <?php if ($withText && !empty($model['title'])) : ?>
        <div class="ReferenceEmbed-Title"><?php echo $model['title'] ?></div>
	<?php endif;
    $slider->insertHtml();
    $slider->enqueueJavascript();
    ?>
</div>