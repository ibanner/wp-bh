(function (window, document) {
'use strict';

var mfApp = angular.module('microfilmApp', [])
    .config(function($httpProvider) {
        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';
    });

mfApp.controller('mfController', function($scope, $http, MicrofilmManager, SortService, YearsService) {
    
    var wp_template_url     = BH_general.params.template,
        views_url           = wp_template_url + '/views/components/',
        api_url             = BH_general.params.api;
        

    var clearForm = function() {
        $scope.microfilms = [];
        $scope.senderDetails = {
            password: ''
        };
        $scope.rc.form.attempted = false;
        $scope.form.$setPristine();
    };

    var _success = false;

    $scope.__defineGetter__('success', function() {
        return _success;
    });

    $scope.__defineSetter__('success', function(bool_val) {
        if ( typeof bool_val == 'boolean' ) {
            _success = bool_val;

            if (bool_val == true) {
                $scope.error.code = null;
                clearForm();
            }
        }
    });

    $scope.templates = {
        countryList: views_url + 'country-list.html',
        stateList: views_url + 'state-list.html',
        provinceList: views_url + 'province-list.html'
    }

    $scope.submitInProgress = false;

    $scope.error = {
        _code: null,

        get code() {
            return this._code;
        },

        set code(code) {
            this._code = code;

            if (code !== null) {
                $scope.success = false;
            }
        }
    };

    $scope.senderDetails = {
        password: ''
    };

    $scope.years = YearsService.years;

    $scope.getMicrofilmData = function() {
       
        $http.get(api_url + 'microfilm-api.php')
            .success(function(result) {
                angular.forEach(result, function(data) {
                    MicrofilmManager.microfilmData.push(data);
                })
            });
    }

    Object.defineProperty($scope, 'microfilmData', {
        get: function() {
            return MicrofilmManager.microfilmData;
        }
    });

    Object.defineProperty($scope, 'microfilms', {
        get: function() {
            return MicrofilmManager.microfilms;
        },

        set: function(microfilmArray) {
            MicrofilmManager.microfilms = microfilmArray;  
        }
    });

    Object.defineProperty($scope, 'letters', {
        get: function() {
            return SortService.letters;
        }
    });

    Object.defineProperty($scope, 'currentLetter', {
        get: function() {
            return SortService.currentLetter;
        },

        set: function(letter) {
            SortService.currentLetter = letter;
        }
    });

    $scope.addMicrofilm = function(data) {
        MicrofilmManager.add(data);
    };

    $scope.removeMicrofilm = function(id) {
        MicrofilmManager.remove(id);
    };

    $scope.changeMicrofilm = function(microfilm) {
        MicrofilmManager.change(microfilm);
    };

    $scope.findMicrofilm = function(id) {
        return MicrofilmManager.findMicrofilm(id);
    };

    $scope.validateMicrofilms = function() {
        var validated = true;

        angular.forEach($scope.microfilms, function(microfilm) {
            if ( !microfilm.valid ) {
                validated = false;
            }
        });

        return validated;
    }

    $scope.findMatch = function(data) {
        return MicrofilmManager.findMatch(data);
    };

    $scope.submitForm = function() {

        if (    !($scope.submitInProgress) && 
                $scope.microfilms.length > 0 && 
                $scope.validateMicrofilms() && 
                $scope.senderDetails.password === '' ) {
            
            $scope.submitInProgress = true;
            
            var postData = {
                microfilms: {}
            };

            angular.forEach($scope.microfilms, function(microfilm, index, microfilms) {
                postData.microfilms[index] = microfilm.data;
            });

            for(var key in $scope.senderDetails) {
                postData[key] = $scope.senderDetails[key];
            }
            
            postData = $.param(postData);
            $http.post(api_url + 'microfilm-api.php', postData)
                .success(function(response) {
                    $scope.show_payment = true;
                }).
                finally(function() {
                    $scope.submitInProgress = false;
                });
        }
    };

    $scope.$watch('senderDetails.country', function(newVal, oldVal) {
        
        if (oldVal == 'United States of America') {
            $scope.senderDetails.state = '';
        }
        else if (oldVal == 'Canada') {
            $scope.senderDetails.province = '';
        }
    });

});

mfApp.factory('MicrofilmManager', function() {

    var MicrofilmManager = function() {
        this.microfilmData = [];
        this.microfilms = [];
    };
    MicrofilmManager.prototype = {

        add: function(data) {

            var microfilm = {
                data: {} 
            };
            
            if (data) {
                microfilm.data.Microfilm = angular.copy(data.Microfilm);
                microfilm.data.Type = angular.copy(data.Type);
                microfilm.valid = true;
            }
            else {
                microfilm.data = {}; 
                microfilm.valid = false;   
            }
            
            this.microfilms.push(microfilm);

            return microfilm;
        },

        findMicrofilm: function(id) {
            var result = false;

            angular.forEach(this.microfilms, function(microfilm){
                if (id == microfilm.data.Microfilm) {
                   result = microfilm;
                }
            });

            return result;
        },

        findMatch: function(data) {
            var result = false,

                compareData = function(data1, data2) {
                    var result = true,
                        compare = ['Microfilm'];

                    compare.forEach(function(prop) {
                        if (data1[prop] != data2[prop]) {
                            result = false;
                        }
                    });

                    return result;
                };

            angular.forEach(this.microfilms, function(microfilm){
                if ( compareData(data, microfilm.data) ) {
                    result = microfilm;
                }
            });

            return result;
        },

        findMicrofilmData: function(id) {
            var result = false;

            angular.forEach(this.microfilmData, function(data){
                if (id == data.Microfilm) {
                    result = data;
                }
            });

            return result;
        },

        remove: function(id) {
            var microfilm = this.findMicrofilm(id);

            if (microfilm) {
                this.microfilms.splice(this.microfilms.indexOf(microfilm), 1);
            }
        },

        change: function(microfilm) {
            var data = this.findMicrofilmData(microfilm.data.Microfilm);

            if (data) {
                microfilm.data.Type = angular.copy(data.Type);
                microfilm.valid = true;
            }
            else {
                microfilm.valid = false;
            }
        }
    };

    return new MicrofilmManager();
});

mfApp.service('SortService', function() {

    this.letters = [];

    for(var l=65; l<=90; l++) {
        this.letters.push( String.fromCharCode(l) );
    }

    this.currentLetter = this.letters[0];

});

mfApp.service('YearsService', function() {
    this.years = [];

    var year = new Date().getFullYear() - 2000;
    for(var y = year; y <= 99; y++) {
        this.years.push(y);
    }
});

mfApp.filter('sortByLetter', function() {
    return function(microfilmData, letter) {
        var filtered = [];

        angular.forEach(microfilmData, function(data) {
            if( data.Town[0].toUpperCase() === letter ) {
                filtered.push(data);
            }
        });

        return filtered;
    }
});

mfApp.directive('showOnHover', function () {
    return {
        restrict: 'A',

        scope: {
            showOnHover: '@'
        },
        
        link: function (scope, element) {
            var hoverClass = scope.showOnHover + '-show';

            element.on('mouseenter', function() {
                $('.' + scope.showOnHover).addClass(hoverClass);
            });
            element.on('mouseleave', function() {
                $('.' + scope.showOnHover).removeClass(hoverClass);
            });
        }
    };
});

mfApp.directive('addPhone', function () {
    return {
        restrict: 'A',

        scope: {
            addPhone: '@'
        },
        
        link: function (scope, element) {
            
            element.click(function() {
                $('#' + scope.addPhone).show();
                element.hide();
            });
        }
    };
});

mfApp.directive('removePhone', function () {
    return {
        restrict: 'A',

        scope: {
            removePhone: '@',
            button: '@showButton'
        },
        
        link: function (scope, element) {

            element.click(function() {
                $('#' + scope.removePhone).hide();
                $('.' + scope.button).show();
                
                scope.$parent.$apply(function(scope) {
                    scope.senderDetails.addphone = '';
                });
            });
        }
    };
});

if (rcSubmitDirective) mfApp.directive(rcSubmitDirective);

})(window, document);