(function ($) {
	"use strict";

	function initMediaField(wrapper) {
		const $wrapper = $(wrapper);
		const fieldId = $wrapper.data("field-id");
		const $field = $(`#${fieldId}`);
		const $preview = $wrapper.find(".media-preview");
		const $selectButton = $wrapper.find(".media-select-button");
		const $removeButton = $wrapper.find(".media-remove-button");

		const mediaType = $wrapper.data("media-type");
		const mediaLibraryTitle = $wrapper.data("media-library-title");
		const mediaLibraryButton = $wrapper.data("media-library-button");
		const previewSize = $wrapper.data("preview-size");
		const buttonText = $wrapper.data("button-text");
		const changeButtonText = $wrapper.data("change-button-text");

		let mediaFrame;

		function updatePreview(attachmentId) {
			if (!attachmentId) {
				$preview.html("").hide();
				return;
			}

			wp.media
				.attachment(attachmentId)
				.fetch()
				.then(function (attachment) {
					let preview = "";
					const type = attachment.type;

					if (
						type === "image" &&
						attachment.sizes &&
						attachment.sizes[previewSize]
					) {
						preview = `<img src="${attachment.sizes[previewSize].url}" alt="" class="media-preview-image" />`;
					} else if (type === "image" && attachment.url) {
						preview = `<img src="${attachment.url}" alt="" class="media-preview-image" />`;
					} else {
						preview =
							`<span class="dashicons dashicons-media-default media-preview-icon"></span><br>` +
							`<span class="media-preview-filename">${attachment.filename || attachment.title || "File"}</span>`;
					}

					$preview.html(preview).show();
				});
		}

		$selectButton.on("click", function (e) {
			e.preventDefault();

			if (mediaFrame) {
				mediaFrame.open();
				return;
			}

			const frameOptions = {
				title: mediaLibraryTitle,
				button: {
					text: mediaLibraryButton,
				},
				multiple: false,
			};

			if (mediaType) {
				frameOptions.library = {
					type: mediaType,
				};
			}

			mediaFrame = wp.media(frameOptions);

			mediaFrame.on("select", function () {
				const attachment = mediaFrame
					.state()
					.get("selection")
					.first()
					.toJSON();
				$field.val(attachment.id);
				updatePreview(attachment.id);
				$wrapper.attr("data-has-media", "true");
				$selectButton.text(changeButtonText);
				$removeButton.show();
			});

			mediaFrame.open();
		});

		$removeButton.on("click", function (e) {
			e.preventDefault();
			$field.val("");
			$preview.html("").hide();
			$wrapper.attr("data-has-media", "false");
			$selectButton.text(buttonText);
			$(this).hide();
		});

		const currentValue = $field.val();
		if (currentValue) {
			updatePreview(currentValue);
		}
	}

	$(document).ready(function () {
		$(".media-field-wrapper").each(function () {
			initMediaField(this);
		});
	});
})(jQuery);
