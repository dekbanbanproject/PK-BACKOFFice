import { IStore, IStateBinder } from '../api/PluginModel';
export declare class ReactStateBinder<TState, TMessage> implements IStateBinder<TState> {
    constructor(initialStore: IStore<TState, TMessage>);
    private _view;
    private _store;
    private _unsubscribe;
    readonly store: IStore<TState, TMessage>;
    private handleStoreUpdate;
    bind(store: IStore<TState, TMessage>): void;
    readonly connect: {
        dispatch: (msg: TMessage) => void;
        ref: (c: any) => void;
    };
    dispatch: (msg: TMessage) => void;
    bindView: (c: any) => void;
}
