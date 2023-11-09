export declare type ProgressDialogSink = {
    onCancel: (error: string) => void;
    onComplete: () => void;
    onProgress: (value: number, message: string) => void;
    cancelled?: Function;
};
