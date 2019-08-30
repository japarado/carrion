"use strict";
document.addEventListener("DOMContentLoaded", function() {
	const addPhotoBtn = document.getElementById("addPhotoBtn");
	addPhotoBtn.addEventListener("click", function(e) {
		e.preventDefault();
		if(canAddPhoto())
		{
			addPhoto();
		}
	});
	initialValues();
});

function initialValues()
{
	const photoBoxes = document.querySelectorAll(".photo-form-display__img-container");
	for(const box of photoBoxes)
	{
		const imageTag = box.querySelectorAll(".photo-form-display__img")[0];
		const inputField = box.querySelectorAll("input[type=\"hidden\"]")[0];
		mediaModalHandler(box, imageTag, inputField);

		const closeButton = box.nextElementSibling;
		closeButtonAction(closeButton);
	}
}

function addPhotoHandler(e)
{
	const addPhotoBtn = document.getElementById("addPhotoBtn");
	addPhotoBtn.addEventListener("click", e => {
		e.preventDefault();
		if(canAddPhoto())
		{
			addPhoto();
		}
	});
}

function canAddPhoto(max = 4)
{
	const currentPhotos = document.querySelectorAll(".photo-form-display__img-container");
	return currentPhotos.length < max;
}

function mediaModalHandler(container, imageTag, inputField)
{
	container.addEventListener("click", e => {
		e.preventDefault();
		let mediaUploader;
		if(mediaUploader)
		{
			mediaUploader.open();
			return;
		}
		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: "Choose Image",
			button: {
				text: "Choose Image"
			},
			multiple: false,
		});
		mediaUploader.on("select", function() {
			const attachment = mediaUploader.state().get("selection").first().toJSON();
			if(attachment.url)
			{
				imageTag.setAttribute("src", attachment.url);
				inputField.setAttribute("value", attachment.url);
			}
		});
		mediaUploader.open();
	});
}

function closeButtonAction(closeButton)
{
	closeButton.addEventListener("click", e => {
		e.preventDefault();
		e.target.parentNode.remove();
	});
}

function addPhoto()
{
	const container = document.getElementById("photoFormDisplay");
	const currentLength = document.querySelectorAll(".photo-form-display__img-container").length;

	const photoBox = new PhotoBox(currentLength + 1).instance();

	container.appendChild(photoBox);
}

class PhotoBox
{
	constructor(id)
	{
		this.id = id;
	}

	instance()
	{
		// const container = this._createContainer();
		// const imageTag = this._createImageTag();
		// const inputField = this._createInputField(); 
		// const closeButton = this._createCloseButton();

		// mediaModalHandler(container, imageTag, inputField);

		// container.appendChild(imageTag);
		// container.appendChild(inputField);
		// container.insertAdjacentElement("afterend", closeButton)

		const container = this._createContainer();
		const uploadField = this._createUploadField();
		const imageTag = this._createImageTag();
		const inputField = this._createInputField();
		const closeButton = this._createCloseButton();

		mediaModalHandler(uploadField, imageTag, inputField);
		closeButtonAction(closeButton);

		uploadField.appendChild(imageTag);
		uploadField.appendChild(inputField);
		container.appendChild(uploadField);
		container.appendChild(closeButton);

		return container;
	}

	_createContainer()
	{
		const container = document.createElement("div");
		container.setAttribute("class", "photo-form-display__container");
		return container;
	}

	_createUploadField()
	{
		const uploadField = document.createElement("div");
		uploadField.setAttribute("id", `upload_image_button${this.id}`);
		uploadField.setAttribute("class", "photo-form-display__img-container");

		return uploadField;
	}

	_createImageTag()
	{
		const imageTag = document.createElement("img");
		imageTag.setAttribute("id", `productImages${this.id}`);
		imageTag.setAttribute("class", "photo-form-display__img");
		return imageTag;
	}

	_createInputField()
	{
		const inputField = document.createElement("input");
		inputField.setAttribute("id", `image${this.id}`);
		inputField.setAttribute("name", "images[]");
		return inputField;
	}

	_createCloseButton()
	{
		const closeButton = document.createElement("div");
		closeButton.setAttribute("class", "photo-form-display__close");
		closeButton.innerHTML = "&times";

		return closeButton;
	}
}

