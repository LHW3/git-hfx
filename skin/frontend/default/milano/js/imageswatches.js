lastVisible = true;
swatchRand = '';
imageSwatchReg = Class.create({
    initialize: function (isProductPage, productid, url, config) {
        this.productid = productid;
        this.config = config;
        this.rand = swatchRand;
        this.isProductPage = isProductPage;
        this.url = url;
        this.defaultInfo = {id: productid};
        this.infoMap = [];
        this.lastRequested = null;
        this.infoMap = [];
        this.productTitle = null;
        this.productImage = null;
        this.shortDescription = null;
        this.fullDescritption = null;
        this.additional = null;
        this.currentInfo = {};
        this.currentInfo.id = this.defaultInfo.id;
    },
    childProductSelected: function (productId) {
        if (!this.infoMap[productId]) {
            this.callController(productId);
        } else {
            this.updateProductInfo(this.infoMap[productId]);
        }
    },
    updateProductInfo: function (info) {
        if (!this.isProductPage)
            return;
        if (info.id) {
            newId = info.id;
        } else {
            newId = this.defaultInfo.id;
        }
        if (this.currentInfo.id != newId) {
            try {
                if (!this.productTitle) {
                    this.productTitle = $$('.product-view .product-name')[0].getElementsByTagName('h1')[0];
                    this.defaultInfo.title = this.productTitle.innerHTML;
                }
            } catch (e) {
            }
            try {
                if (!this.productImage) {
                    this.productImage = $$('#product_tabs_gallery_contents')[0];
                    this.defaultInfo.image = this.productImage.innerHTML;
                }
            } catch (e) {
            }
            try {
                if (!this.shortDescription) {
                    this.shortDescription = $$('.short-description')[0].getElementsByClassName('std')[0];
                    this.defaultInfo.shortDescription = this.shortDescription.innerHTML;
                }
            } catch (e) {
            }
            try {
                if (!this.fullDescritption) {
                    this.fullDescritption = $$('#product_tabs_description_contents')[0];
                    this.defaultInfo.fullDescritption = this.fullDescritption.innerHTML;
                }
            } catch (e) {
            }
            try {
                if (!this.additional) {
                    this.additional = $$('#product_accordion_additional_contents')[0];
                    this.defaultInfo.additional = this.additional.innerHTML;
                }
            } catch (e) {
            }
            try {
                if (info.title) {
                    newTitle = info.title;
                } else {
                    newTitle = this.defaultInfo.title;
                }
                if (this.productTitle) {
                    this.productTitle.innerHTML = newTitle;
                }

                if (info.shortDescription) {
                    newShortDescription = info.shortDescription;
                } else {
                    newShortDescription = this.defaultInfo.shortDescription;
                }
                if (this.shortDescription) {
                    this.shortDescription.innerHTML = newShortDescription;
                }

                if (info.fullDescritption) {
                    newFullDescritption = info.fullDescritption;
                } else {
                    newFullDescritption = this.defaultInfo.fullDescritption;
                }
                if (this.fullDescritption) {
                    this.fullDescritption.innerHTML = newFullDescritption;
                }

                if (info.image) {
                    newImage = info.image;
                } else {
                    newImage = this.defaultInfo.image;
                }
                if (this.productImage) {
                    this.productImage.innerHTML = newImage;
                    jQuery.initProductMedia();
                }

                if (info.additional) {
                    newAdditional = info.additional;
                } else {
                    newAdditional = this.defaultInfo.additional;
                }
                if (this.additional) {
                    this.additional.innerHTML = newAdditional;
                }

                enableZoom();
            } catch (e) {
                //console.warn(e)
            }
            this.currentInfo.id = info.id;
        }
    },
    callController: function (productId) {
        if (!this.isProductPage)
            return;
        if (this.lastRequested == productId)
            return;
        this.lastRequested = productId;
        new Ajax.Request(this.url,
                {
                    method: 'post',
                    parameters: {id: productId},
                    onSuccess: function (resp) {
                        var response = resp.responseText || " ";
                        error = false;
                        try {
                            imageSwatch.infoMap[productId] = response.evalJSON();
                            if (imageSwatch.infoMap[productId].image) {
                                matches = imageSwatch.infoMap[productId].image.match(/\/catalog\/product\/gallery\//g);
                                if (matches)
                                    for (i = 0; i < matches.length; i++) {
                                        imageSwatch.infoMap[productId].image = imageSwatch.infoMap[productId].image.replace('/catalog/product/gallery/', '/imageswatches/index/gallery/');
                                    }
                            }
                        } catch (e) {
                            console.warn(response);
                            error = true;
                        }
                        imageSwatch.lastRequested = null;
                        if (!error)
                            imageSwatch.childProductSelected(productId);
                    },
                    onFailure: function (resp) {
                        var response = resp.responseText || " ";
                        imageSwatch.lastRequested = null;
                        console.warn(response);
                    }
                }
        );
    }

}
);

imageSwatchOptions = Class.create(
        {
            initialize: function (config) {
                this.rand = swatchRand;
                this.config = config;
                this.config.settings.each(function (el) {
                    Event.observe(el, 'change', function () {
                        eval('resetImageSwatchOptions' + swatchRand + '(this)')
                    });
                });
                selectedOptions = [];
                this.lastClick = null;
            },
            resetSelection: function (select) {

                this.hideOptions();
                this.config.settings.each(function (el) {
                    if (el.hasClassName('hidden-select') && !el.disabled) {
                        for (i = 1; i < el.options.length; i++) {
                            $(el.id + '_' + el.options[i].config.id).parentElement.addClassName('active').show();
                        }
                    }
                    if (el.disabled) {
                        for (i = 1; i < el.options.length; i++) {
                            try {
                                $(el.id + '_' + el.options[i].config.id).removeClassName('selected');
                                $(el.id + '_' + el.options[i].config.id).parentElement.removeClassName('selected');
                                $(el.id + '_' + el.options[i].config.id).parentElement.hide();
                            } catch (e) {
                            }
                        }
                    }
                });
                if (select) {
                    this.forceSelection(select.id);
                }
                this.displayAdvises();
                //Update product info (description, title, image, attributes)
                if (this.config.settings.last().selectedIndex != 0) {
                    lastSelect = this.config.settings.last();
                    if (lastSelect.selectedIndex != 0) {
                        eval('imageSwatch' + this.rand).childProductSelected(lastSelect.options[lastSelect.selectedIndex].config.allowedProducts);
                    }
                } else {
                    eval('imageSwatch' + this.rand).updateProductInfo(eval('imageSwatch' + this.rand).defaultInfo);
                }
            },
            displayAdvises: function () {
                $$('.swatch_container').each(function (container) {
                    adviseFlag = true;

                    options = container.getElementsByClassName('swatch-option');
                    for (i = 0; i < options.length; i++) {
                        if ($(options[i].id.split(/\_/)[0]).disabled) {
                            options[i].hide();
                        }
                        if (options[i].visible()) {
                            adviseFlag = false;
                            break;
                        }
                    }
                    if (adviseFlag) {
                        container.getElementsByClassName('advise_swatch')[0].style.display = 'block';
                    } else {
                        container.getElementsByClassName('advise_swatch')[0].style.display = 'none';
                    }
                });
            },
            hideOptions: function () {
                optionorder = 0;
                $$('.swatch-img').each(function (el) {
                    if (optionorder > -1)
                        el.parentElement.hide();
                    optionorder++
                });
            },
            mouseHover: function (element) {
                if (touched) {
                    return;
                }
                swatchTimeout[element.id] = true;
                setTimeout(function () {
                    if (!swatchTimeout[element.id]) {
                        return;
                    }
                    var showDivId = 'full_image_' + element.id;
                    $(showDivId).removeClassName('is-hidden');
                    $(showDivId).addClassName('is-popup');
                }
                , 1000);
            },
            mouseOut: function (element) {
                swatchTimeout[element.id] = false;
                var showDivId = 'full_image_' + element.id;
                $(showDivId).removeClassName('popup');
                $(showDivId).addClassName('hidden');
            },
            forceSelection: function (selectId) {

                selectVal = $(selectId).value;
                try {
                    $(selectId + '_' + selectVal).click();

                } catch (e) {
                    //lastVisible = false;
                }
                order = 0;
                foundFlag = false;
                this.config.settings.each(function (select) {
                    if ((select.id == selectId) && !foundFlag) {
                        foundFlag = true;
                        return;
                    }
                    if (!foundFlag) {
                        order++;
                    }
                });
                visFlag = false;
                counter = 0;
                order++;
                //now we should click all visible selected elements under current clicked
                if (!this.config.settings[order])
                    return;
                if (!$('swatch-' + this.config.settings[order].id))
                    return;
                childOptions = $('swatch-' + this.config.settings[order].id).getElementsByClassName('selected');

                if (childOptions.length > 0) {
                    if (childOptions[0].visible()) {
                        if (lastVisible) {
                            childOptions[0].click();
                            lastVisible = true;
                        } else {
                            lastVisible = false;
                            childOptions[0].removeClassName('selected');
                            childOptions[0].parentElement.removeClassName('selected');
                        }
                    }
                }
            },
            optionSelected: function (element) {
                var clicked = element.id;
                if (this.lastClick == clicked)
                    return;
                this.lastClick = clicked;
                splited = clicked.split(/\_/);
                selectId = splited[0];
                var valueId = splited[1];
                if (window.active !== undefined) {
                    activeSplit = active.split(/\_/);
                    if ((activeSplit[0] == selectId)) {
                        $(active).removeClassName('selected');
                        $(active).parentElement.removeClassName('selected');
                    }
                }
                select = document.getElementById(selectId);
                var elems = select.getElementsByTagName('option');

                for (i = 0; i < elems.length; i++) {
                    if (elems[i].value == valueId) {
                        if (select.disabled) {
                            return;
                        }
                        elems[i].selected = true;
                        siblings = element.parentElement.parentElement.getElementsByClassName('selected');
                        for (j = 0; j < siblings.length; j++) {
                            siblings[0].removeClassName('selected');
                            siblings[0].parentElement.removeClassName('selected');
                        }
                        $(clicked).addClassName('selected');
                        $(clicked).parentElement.addClassName('selected');
                    }
                }

                this.config.configureElement($(selectId));
                eval('imSwatchOptions' + this.rand).resetSelection($(selectId));
            }
        }
);
function in_array(what, where) {
    for (var i = 0; i < where.length; i++)
        if (what == where[i])
            return true;
    return false;
}

function enableZoom() {
    if ($('image')) {
        Event.observe($('image'), 'load', function () {
            product_zoom = new Product.Zoom('image', 'track', 'handle', 'zoom_in', 'zoom_out', 'track_hint');
        });
    }
}
