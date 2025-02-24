# -*- coding: utf-8 -*- #
# Copyright 2018 Google LLC. All Rights Reserved.
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
"""The gcloud datastore indexes create command."""

from __future__ import absolute_import
from __future__ import division
from __future__ import unicode_literals

from googlecloudsdk.api_lib.app import yaml_parsing
from googlecloudsdk.api_lib.datastore import index_api
from googlecloudsdk.calliope import base
from googlecloudsdk.calliope import exceptions
from googlecloudsdk.command_lib.app import output_helpers
from googlecloudsdk.core import properties
from googlecloudsdk.core.console import console_io


@base.ReleaseTracks(base.ReleaseTrack.BETA, base.ReleaseTrack.GA)
class Create(base.Command):
  """Create Cloud Datastore indexes."""

  detailed_help = {
      'brief': (
          'Create new datastore indexes based on your local index '
          'configuration.'
      ),
      'DESCRIPTION': """
Create new datastore indexes based on your local index configuration.
Any indexes in your index file that do not exist will be created.
      """,
      'EXAMPLES': """\
          To create new indexes based on your local configuration, run:

            $ {command} ~/myapp/index.yaml

          Detailed information about index configuration can be found at the
          [index.yaml reference](https://cloud.google.com/appengine/docs/standard/python/config/indexref).
          """,
      }

  @staticmethod
  def Args(parser):
    """Get arguments for this command.

    Args:
      parser: argparse.ArgumentParser, the parser for this command.
    """
    parser.add_argument(
        'index_file',
        help="""
        The path to your `index.yaml` file. For a detailed look into defining
        your `index.yaml` file, refer to this configuration guide:
        https://cloud.google.com/datastore/docs/tools/indexconfig#Datastore_About_index_yaml
        """,
    )

  def Run(self, args):
    return self.CreateIndexes(index_file=args.index_file)

  def CreateIndexes(self, index_file, database=None):
    project = properties.VALUES.core.project.Get(required=True)
    info = yaml_parsing.ConfigYamlInfo.FromFile(index_file)
    if not info or info.name != yaml_parsing.ConfigYamlInfo.INDEX:
      raise exceptions.InvalidArgumentException(
          'index_file', 'You must provide the path to a valid index.yaml file.'
      )
    output_helpers.DisplayProposedConfigDeployments(project, [info])
    console_io.PromptContinue(
        default=True, throw_if_unattended=False, cancel_on_no=True
    )

    if database:
      # Use Firestore Admin for non (default) database.
      index_api.CreateIndexesViaFirestoreApi(project, database, info.parsed)
    else:
      # Use Datastore Admin for the (default) database.
      index_api.CreateMissingIndexes(project, info.parsed)


@base.ReleaseTracks(base.ReleaseTrack.ALPHA)
class CreateFirestoreAPI(Create):
  """Create Cloud Datastore indexes with Firestore API."""

  @staticmethod
  def Args(parser):
    """Get arguments for this command.

    Args:
      parser: argparse.ArgumentParser, the parser for this command.
    """
    parser.add_argument(
        'index_file',
        help="""
        The path to your `index.yaml` file. For a detailed look into defining
        your `index.yaml` file, refer to this configuration guide:
        https://cloud.google.com/datastore/docs/tools/indexconfig#Datastore_About_index_yaml
        """,
    )
    parser.add_argument(
        '--database',
        help='The database to operate on.',
        type=str,
    )

  def Run(self, args):
    return self.CreateIndexes(
        index_file=args.index_file, database=args.database
    )
