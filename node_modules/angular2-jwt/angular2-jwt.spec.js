"use strict";
require("core-js");
var angular2_jwt_1 = require("./angular2-jwt");
var angular2_jwt_2 = require("./angular2-jwt");
var angular2_jwt_3 = require("./angular2-jwt");
var rxjs_1 = require("rxjs");
var expiredToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjB9.m2OKoK5-Fnbbg4inMrsAQKsehq2wpQYim8695uLdogk";
var validToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjk5OTk5OTk5OTl9.K_lUwtGbvjCHP8Ff-gW9GykydkkXzHKRPbACxItvrFU";
var noExpiryToken = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWV9.TJVA95OrM7E2cBab30RMHrHDcEfxjoYZgeFONFh7HgQ";
describe('AuthConfig', function () {
    'use strict';
    it('should have default values', function () {
        var config = new angular2_jwt_1.AuthConfig().getConfig();
        expect(config).toBeDefined();
        expect(config.headerName).toBe("Authorization");
        expect(config.headerPrefix).toBe("Bearer ");
        expect(config.tokenName).toBe("id_token");
        expect(config.noJwtError).toBe(false);
        expect(config.noTokenScheme).toBe(false);
        expect(config.globalHeaders).toEqual([]);
        expect(config.tokenGetter).toBeDefined();
        var token = "Token";
        localStorage.setItem(config.tokenName, token);
        expect(config.tokenGetter()).toBe(token);
    });
    it('should have default values', function () {
        var configExpected = {
            headerName: "Foo",
            headerPrefix: "Bar",
            tokenName: "token",
            tokenGetter: function () { return "this is a token"; },
            noJwtError: true,
            globalHeaders: [{ "header": "value" }, { "header2": "value2" }],
            noTokenScheme: true
        };
        var config = new angular2_jwt_1.AuthConfig(configExpected).getConfig();
        expect(config).toBeDefined();
        expect(config.headerName).toBe(configExpected.headerName);
        expect(config.headerPrefix).toBe(configExpected.headerPrefix + " ");
        expect(config.tokenName).toBe(configExpected.tokenName);
        expect(config.noJwtError).toBe(configExpected.noJwtError);
        expect(config.noTokenScheme).toBe(configExpected.noTokenScheme);
        expect(config.globalHeaders).toEqual(configExpected.globalHeaders);
        expect(config.tokenGetter).toBeDefined();
        expect(config.tokenGetter()).toBe("this is a token");
    });
});
describe('JwtHelper', function () {
    'use strict';
    describe('urlBase64Decode', function () {
    });
    describe('decodeToken', function () {
    });
    describe('getTokenExpirationDate', function () {
    });
    describe('isTokenExpired', function () {
        var jwtHelper;
        beforeEach(function () {
            jwtHelper = new angular2_jwt_3.JwtHelper();
        });
        it('should return false when the token is not expired', function () {
            var actual = jwtHelper.isTokenExpired(validToken);
            expect(actual).toBe(false);
        });
        it('should return true when the token is expired', function () {
            var actual = jwtHelper.isTokenExpired(expiredToken);
            expect(actual).toBe(true);
        });
        it('should return false when the token doesn\'t have an expiry date', function () {
            var actual = jwtHelper.isTokenExpired(noExpiryToken);
            expect(actual).toBe(false);
        });
        // it('should return false when the token is expired, but within the grace period', ()=> {
        //     console.log("test start");
        //     // return a date that has expired 5 seconds ago
        //     jwtHelper.getTokenExpirationDate=(token:string)=>{
        //         const date=new Date(new Date().valueOf()-5000);
        //         console.log("token date",date);
        //         console.log("actual date",new Date());
        //         return date;
        //     };
        //     //token doesn't matter because we mocked getTokenExpirationDate
        //     const tokenExpired:boolean=jwtHelper.isTokenExpired("");
        //     expect(tokenExpired).toBe(true,"token should be expired");
        //     const tokenExpired:boolean=jwtHelper.isTokenExpired("",6);
        //     expect(tokenExpired).toBe(false,"token should be within the grace period");
        //     console.log("test end");
        // });
        // it('should return true when the token is expired and outside the grace period', ()=> {
        //     // return a date that has expired 5 seconds ago
        //     jwtHelper.getTokenExpirationDate=(token:string)=>new Date(new Date().valueOf()-5000);
        //     //token doesn't matter because we mocked getTokenExpirationDate
        //     const tokenExpired:boolean=jwtHelper.isTokenExpired("");
        //     expect(tokenExpired).toBe(false,"token should be expired");
        //     const tokenExpired:boolean=jwtHelper.isTokenExpired("",3);
        //     expect(tokenExpired).toBe(true,"token should not be within the grace period");
        // });
    });
});
describe('tokenNotExpired', function () {
    'use strict';
    it('should use the passed token when not expired', function () {
        var actual = angular2_jwt_2.tokenNotExpired(null, validToken);
        expect(actual).toBe(true);
    });
    it('should use the passed token when expired', function () {
        var actual = angular2_jwt_2.tokenNotExpired(null, expiredToken);
        expect(actual).toBe(false);
    });
    it('should use the passed tokenName when not expired', function () {
        localStorage.setItem("Valid", validToken);
        var actual = angular2_jwt_2.tokenNotExpired("Valid");
        expect(actual).toBe(true);
    });
    it('should use the passed tokenName when expired', function () {
        localStorage.setItem("Expired", expiredToken);
        var actual = angular2_jwt_2.tokenNotExpired("Expired");
        expect(actual).toBe(false);
    });
    it('should use the defaults when not expired', function () {
        localStorage.setItem("id_token", validToken);
        var actual = angular2_jwt_2.tokenNotExpired();
        expect(actual).toBe(true);
    });
    it('should use the defaults when expired', function () {
        localStorage.setItem("id_token", expiredToken);
        var actual = angular2_jwt_2.tokenNotExpired();
        expect(actual).toBe(false);
    });
});
describe("AuthHttp", function () {
    describe("request", function () {
        it("handles tokenGetters returning string", function () {
            var authHttp = new angular2_jwt_1.AuthHttp(new angular2_jwt_1.AuthConfig({
                tokenGetter: function () { return validToken; }
            }), null);
            spyOn(authHttp, "requestWithToken").and.stub();
            authHttp.request(null);
            expect(authHttp["requestWithToken"]).toHaveBeenCalledWith(null, validToken);
        });
        it("handles tokenGetters returning Promise\<string\>", function (done) {
            var authHttp = new angular2_jwt_1.AuthHttp(new angular2_jwt_1.AuthConfig({
                tokenGetter: function () { return Promise.resolve(validToken); }
            }), null);
            spyOn(authHttp, "requestWithToken").and.returnValue(rxjs_1.Observable.of(""));
            authHttp.request(null).subscribe(function () {
                expect(authHttp["requestWithToken"]).toHaveBeenCalledWith(null, validToken);
                done();
            });
        });
    });
});
//# sourceMappingURL=angular2-jwt.spec.js.map