humhub.module('polls', function (module, require, $) {
    var object = require('util').object;
    var client = require('client');
    var Content = require('content').Content;

    var Poll = function (id) {
        Content.call(this, id);
    };

    object.inherits(Poll, Content);

    Poll.prototype.vote = function (submitEvent) {
        if (submitEvent.$form.find("input:checked").length) {
            this.update(client.submit(submitEvent));
        } else {
            module.log.warn("warn.answer_required", true);
            setTimeout(function() {submitEvent.finish()}, 50);
        }
    };

    Poll.prototype.close = function (event) {
        this.update(client.post(event));
    };

    Poll.prototype.update = function (update) {
        this.loader();
        update.then($.proxy(this.handleUpdateSuccess, this))
            .catch(Poll.handleUpdateError)
            .finally($.proxy(this.loader, this, false));
    };

    Poll.prototype.handleUpdateSuccess = function (response) {
        var streamEntry = this.streamEntry();
        return streamEntry.replace(response.output).then(function () {
            module.log.success('success.saved');
        });
    };

    Poll.prototype.editSubmit = function (evt) {
        var that = this;
        var $errorMessage = that.$.find('.errorMessage');
        this.loader();
        $errorMessage.parent().addClass('d-none');
        client.submit(evt).then(function (response) {
            if (!response.errors) {
                that.handleUpdateSuccess(response);
            } else {
                var errors = '';
                $.each(response.errors, function (key, value) {
                    errors += value + '<br />';
                });
                $errorMessage.html(errors).parent().removeClass('d-none');
            }
        }).catch(Poll.handleUpdateError)
            .finally($.proxy(this.loader, this, false));
    };

    Poll.prototype.reset = function (evt) {
        this.update(client.post(evt));
    };

    Poll.prototype.editCancel = function (evt) {
        this.update(client.post(evt));
    };

    Poll.prototype.removePollAnswer = function (evt) {
        evt.$trigger.closest('.mb-3').remove();
    };

    Poll.prototype.addPollAnswer = function (evt) {
        var $this = evt.$trigger;
        $this.prev('input').tooltip({
            html: true,
            container: 'body'
        });

        var $newInputGroup = $this.closest('.mb-3').clone(false);
        var $input = $newInputGroup.find('input');

        $input.val('');
        $newInputGroup.addClass('d-none');
        $this.closest('.mb-3').after($newInputGroup);
        $this.children('span').removeClass('fa-plus').addClass('fa-trash');
        $this.off('click.humhub-action').on('click', function () {
            $this.closest('.mb-3').remove();
        });
        $this.removeAttr('data-action-click');
        $newInputGroup.removeClass('d-none');
    };

    Poll.prototype.loader = function ($loader) {
        this.streamEntry().loader($loader);
    };

    Poll.prototype.streamEntry = function () {
        return this.parent();
    };

    Poll.handleUpdateError = function (e) {
        module.log.error(e, true);
    };

    module.export({
        Poll: Poll
    });
});