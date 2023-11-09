export declare type Model = {
    progress: {
        isOpen: false;
    } | {
        isOpen: true;
        message: string;
    };
    title: string;
    showCancel: boolean;
};
export declare type Msg = {
    type: "setProgress";
    payload: {
        isOpen: false;
    } | {
        isOpen: true;
        message: string;
    };
} | {
    type: "init";
    payload: {
        title: string;
        showCancel: boolean;
    };
};
