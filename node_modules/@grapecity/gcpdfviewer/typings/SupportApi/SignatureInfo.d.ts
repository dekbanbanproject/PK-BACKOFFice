export declare type SignatureInfo = {
    ContactInfo: string;
    Location: string;
    SignerName: string;
    Reason: string;
    SignatureDigestAlgorithm: 'SHA1' | 'SHA256' | 'SHA384' | 'SHA512' | 'PKCS7SHA1';
    SignatureFormat: 'PKCS7Detached' | 'PKCS7SHA1';
    TimeStamp: {
        ServerUrl?: string;
        UserName?: string;
        Password?: string;
    };
    SignatureField: string;
};
