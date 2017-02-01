humhub.module('polls', function (module, require, $) {
    var object = require('util').object;
    var client = require('client');
    var Content = require('content').Content;

    var Poll = function (id) {
        Content.call(this, id);
    };

    object.inherits(Poll, Content);

    Poll.prototype.vote = function (submitEvent) {
        this.update(client.submit(submitEvent));
    };

    Poll.prototype.close = function (event) {
        this.update(client.post(event));
    };

    Poll.prototype.update = function (update) {
        this.streamEntry().loader();
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

    Poll.prototype.loader = function ($loader) {
        this.streamEntry().loader($loader);
    };

    Poll.prototype.streamEntry = function (evt) {
        return this.parent();
    };

    Poll.handleUpdateError = function (e) {
        module.log.error(e, true);
    };

    module.export({
        Poll: Poll
    });
});