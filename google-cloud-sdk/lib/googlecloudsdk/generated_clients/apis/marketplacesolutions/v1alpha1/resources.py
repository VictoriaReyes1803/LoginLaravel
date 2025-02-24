# -*- coding: utf-8 -*- #
# Copyright 2023 Google LLC. All Rights Reserved.
#
# Licensed under the Apache License, Version 2.0 (the "License");
# you may not use this file except in compliance with the License.
# You may obtain a copy of the License at
#
#    http://www.apache.org/licenses/LICENSE-2.0
#
# Unless required by applicable law or agreed to in writing, software
# distributed under the License is distributed on an "AS IS" BASIS,
# WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
# See the License for the specific language governing permissions and
# limitations under the License.
"""Resource definitions for Cloud Platform Apis generated from apitools."""

import enum


BASE_URL = 'https://marketplacesolutions.googleapis.com/v1alpha1/'
DOCS_URL = 'https://cloud.google.com/bare-metal'


class Collections(enum.Enum):
  """Collections for all supported apis."""

  PROJECTS = (
      'projects',
      'projects/{projectsId}',
      {},
      ['projectsId'],
      True
  )
  PROJECTS_LOCATIONS = (
      'projects.locations',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_AZUREINSTANCES = (
      'projects.locations.azureInstances',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/azureInstances/'
              '{azureInstancesId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_BAREMETALINSTANCES = (
      'projects.locations.bareMetalInstances',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/'
              'bareMetalInstances/{bareMetalInstancesId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_CONVERGEIMAGES = (
      'projects.locations.convergeImages',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/convergeImages/'
              '{convergeImagesId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_CONVERGEINSTANCES = (
      'projects.locations.convergeInstances',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/'
              'convergeInstances/{convergeInstancesId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_CONVERGENETWORKS = (
      'projects.locations.convergeNetworks',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/'
              'convergeNetworks/{convergeNetworksId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_CONVERGESSHKEYS = (
      'projects.locations.convergeSshKeys',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/convergeSshKeys/'
              '{convergeSshKeysId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_CONVERGEVOLUMES = (
      'projects.locations.convergeVolumes',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/convergeVolumes/'
              '{convergeVolumesId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_FAKES = (
      'projects.locations.fakes',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/fakes/{fakesId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_NETAPPVOLUMES = (
      'projects.locations.netappVolumes',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/netappVolumes/'
              '{netappVolumesId}',
      },
      ['name'],
      True
  )
  PROJECTS_LOCATIONS_OPERATIONS = (
      'projects.locations.operations',
      '{+name}',
      {
          '':
              'projects/{projectsId}/locations/{locationsId}/operations/'
              '{operationsId}',
      },
      ['name'],
      True
  )

  def __init__(self, collection_name, path, flat_paths, params,
               enable_uri_parsing):
    self.collection_name = collection_name
    self.path = path
    self.flat_paths = flat_paths
    self.params = params
    self.enable_uri_parsing = enable_uri_parsing
