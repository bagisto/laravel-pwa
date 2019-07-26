/**
 * @license
 * Copyright 2017 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
import { Query } from '../core/query';
import { DocumentKeySet, DocumentMap, DocumentSizeEntries, DocumentSizeEntry, MaybeDocumentMap, NullableMaybeDocumentMap } from '../model/collections';
import { MaybeDocument } from '../model/document';
import { DocumentKey } from '../model/document_key';
import { IndexManager } from './index_manager';
import { PersistenceTransaction } from './persistence';
import { PersistencePromise } from './persistence_promise';
import { RemoteDocumentCache } from './remote_document_cache';
import { RemoteDocumentChangeBuffer } from './remote_document_change_buffer';
export declare type DocumentSizer = (doc: MaybeDocument) => number;
export declare class MemoryRemoteDocumentCache implements RemoteDocumentCache {
    private readonly indexManager;
    private readonly sizer;
    private docs;
    private newDocumentChanges;
    private size;
    /**
     * @param sizer Used to assess the size of a document. For eager GC, this is expected to just
     * return 0 to avoid unnecessarily doing the work of calculating the size.
     */
    constructor(indexManager: IndexManager, sizer: DocumentSizer);
    /**
     * Adds the supplied entries to the cache. Adds the given size delta to the cached size.
     */
    addEntries(transaction: PersistenceTransaction, entries: DocumentSizeEntry[], sizeDelta: number): PersistencePromise<void>;
    /**
     * Removes the specified entry from the cache and updates the size as appropriate.
     */
    removeEntry(transaction: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<number>;
    getEntry(transaction: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<MaybeDocument | null>;
    /**
     * Looks up an entry in the cache.
     *
     * @param documentKey The key of the entry to look up.
     * @return The cached MaybeDocument entry and its size, or null if we have nothing cached.
     */
    getSizedEntry(transaction: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<DocumentSizeEntry | null>;
    getEntries(transaction: PersistenceTransaction, documentKeys: DocumentKeySet): PersistencePromise<NullableMaybeDocumentMap>;
    /**
     * Looks up several entries in the cache.
     *
     * @param documentKeys The set of keys entries to look up.
     * @return A map of MaybeDocuments indexed by key (if a document cannot be
     *     found, the key will be mapped to null) and a map of sizes indexed by
     *     key (zero if the key cannot be found).
     */
    getSizedEntries(transaction: PersistenceTransaction, documentKeys: DocumentKeySet): PersistencePromise<DocumentSizeEntries>;
    getDocumentsMatchingQuery(transaction: PersistenceTransaction, query: Query): PersistencePromise<DocumentMap>;
    forEachDocumentKey(transaction: PersistenceTransaction, f: (key: DocumentKey) => PersistencePromise<void>): PersistencePromise<void>;
    getNewDocumentChanges(transaction: PersistenceTransaction): PersistencePromise<MaybeDocumentMap>;
    newChangeBuffer(): RemoteDocumentChangeBuffer;
    getSize(txn: PersistenceTransaction): PersistencePromise<number>;
}
/**
 * Handles the details of adding and updating documents in the MemoryRemoteDocumentCache.
 */
export declare class MemoryRemoteDocumentChangeBuffer extends RemoteDocumentChangeBuffer {
    private readonly sizer;
    private readonly documentCache;
    constructor(sizer: DocumentSizer, documentCache: MemoryRemoteDocumentCache);
    protected applyChanges(transaction: PersistenceTransaction): PersistencePromise<void>;
    protected getFromCache(transaction: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<DocumentSizeEntry | null>;
    protected getAllFromCache(transaction: PersistenceTransaction, documentKeys: DocumentKeySet): PersistencePromise<DocumentSizeEntries>;
}
