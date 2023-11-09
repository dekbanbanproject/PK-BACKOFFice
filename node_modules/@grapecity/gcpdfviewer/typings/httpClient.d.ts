import 'whatwg-fetch';
export declare type TypedResponse<T> = {
    ok: true;
    status: number;
    response: T;
} | {
    ok: false;
    requestError: string;
};
export declare type IHttpClient = {
    getJson: <TR>(uri: string) => Promise<TypedResponse<TR>>;
    postJson: <TR>(uri: string, data: unknown) => Promise<TypedResponse<TR>>;
    getText: (uri: string) => Promise<TypedResponse<string>>;
};
export declare function createHttpClient(baseUri: string, authToken: string): IHttpClient;
