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
import { Query } from '../../../src/core/query';
import { IndexedDbPersistence } from '../../../src/local/indexeddb_persistence';
import { MemoryPersistence } from '../../../src/local/memory_persistence';
import { Persistence, PersistenceTransaction } from '../../../src/local/persistence';
import { PersistencePromise } from '../../../src/local/persistence_promise';
import { RemoteDocumentCache } from '../../../src/local/remote_document_cache';
import { RemoteDocumentChangeBuffer } from '../../../src/local/remote_document_change_buffer';
import { DocumentKeySet, DocumentMap, MaybeDocumentMap, NullableMaybeDocumentMap } from '../../../src/model/collections';
import { MaybeDocument } from '../../../src/model/document';
import { DocumentKey } from '../../../src/model/document_key';
/**
 * A wrapper around a RemoteDocumentCache that automatically creates a
 * transaction around every operation to reduce test boilerplate.
 */
export declare abstract class TestRemoteDocumentCache {
    private readonly persistence;
    protected cache: RemoteDocumentCache;
    protected constructor(persistence: Persistence, cache: RemoteDocumentCache);
    /**
     * Reads all of the documents first so we can safely add them and keep the size calculation in
     * sync.
     */
    addEntries(maybeDocuments: MaybeDocument[]): Promise<void>;
    removeEntry(documentKey: DocumentKey): Promise<number>;
    protected abstract removeInternal(txn: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<number>;
    getEntry(documentKey: DocumentKey): Promise<MaybeDocument | null>;
    getEntries(documentKeys: DocumentKeySet): Promise<NullableMaybeDocumentMap>;
    getDocumentsMatchingQuery(query: Query): Promise<DocumentMap>;
    getNewDocumentChanges(): Promise<MaybeDocumentMap>;
    removeDocumentChangesThroughChangeId(changeId: number): Promise<void>;
    getSize(): Promise<number>;
    newChangeBuffer(): RemoteDocumentChangeBuffer;
}
export declare class TestMemoryRemoteDocumentCache extends TestRemoteDocumentCache {
    private memoryCache;
    constructor(persistence: MemoryPersistence);
    protected removeInternal(txn: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<number>;
}
export declare class TestIndexedDbRemoteDocumentCache extends TestRemoteDocumentCache {
    private indexedDbCache;
    constructor(persistence: IndexedDbPersistence);
    /**
     * In contrast with the memory implementation, the IndexedDb implementation expects the remove
     * caller to call update size after removing a series of documents. Mimic that for the tests.
     */
    protected removeInternal(txn: PersistenceTransaction, documentKey: DocumentKey): PersistencePromise<number>;
}
